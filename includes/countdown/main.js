/*
 This file was generated by Dashcode.
 You may edit this file to customize your widget or web page
 according to the license.txt file included in the project.
 */

// Pre-calculate some time constants
var msPerSecond = 1000;
var sPerMinute  = 60;
var sPerHour    = 60 * sPerMinute;
var sPerDay     = 24 * sPerHour;

// Properties set by attributes panel
var targetKind;
var countdownTarget;
var wantLeadingZeros;
var wantBlinkingColons;
var wantStopAtZero;
var doAction;
var zeroAction;
var iCalEventURL = "__iCalEvent__.ics";
var iCalEventSummary;
var sharediCalURL;

// JavaScript interval timer to update clock
var updateTimerDisplayInterval;

// Clock registers
var currentTime;
var remainingSeconds;
var remainingTime = new Array(4);
var iCalendar;

// Flag for whether we've reached/passed the target time
var isTargetReached = false;

//
// Function: updateTimerDisplay()
// The main loop called by our timer interval.
// Update the clock, display the new time, and run the alarm if the target is reached.
//
function updateTimerDisplay()
{
    calculateRemainingTime();
    formatTimerDisplay();
    checkForAlarm();
}

//
// Function: formatTimerDisplay()
// Called by updateTimerDisplay() when the timer's countdown display needs to be updated.
// The remainingTime array will contain the number of days, hours, minutes, and seconds
// left until the current target event.
// Customize this function if you need to to display the time in a different way.
//
function formatTimerDisplay()
{
    setElementText("remaining-days",    remainingTime[0]);
    setElementText("remaining-hours",   formatTwoDigits(remainingTime[1]));
    setElementText("remaining-minutes", formatTwoDigits(remainingTime[2]));
    setElementText("remaining-seconds", formatTwoDigits(remainingTime[3]));

    // Blink the colons every other second if desired
    var isVisible = true;
    if (wantBlinkingColons) {
        var isVisible = Math.floor(currentTime / 1000) % 2 ? "hidden" : "visible";
    }
    var colonDiv = document.getElementById("timer-colons");
    if (colonDiv)
        colonDiv.style.visibility = isVisible;
}

//
// Function: formatTwoDigits(number)
// Format a number as one or two digits with a leading zero if needed
//
// number: The number to format
//
// Returns the formatted number as a string.
//
function formatTwoDigits(number)
{
    var digits = number.toString(10);

    // Add a leading zero if it's only one digit long
    if (wantLeadingZeros && digits.length == 1) {
        digits = "0" + digits;
    }

    return digits;
}

//
// Function: setEventLabel(label)
// Format and display the countdown label
// You may need to customize this function if you remove or change the event-label div
//
// label: Text to display for the label
//
function setEventLabel(label)
{
    var eventLabelElement = document.getElementById("event-label");
    if (eventLabelElement) {
        var formatString = dashcode.getLocalizedString("Countdown to %s");
        var labelString = formatString.replace("%s", label);
        eventLabelElement.innerText = labelString;
    }
}

//
// Function: setElementText(elementName, elementValue)
// Set the text contents of an HTML div
//
// elementName: Name of the element in the DOM
// elementValue: Text to display in the element
//
function setElementText(elementName, elementValue)
{
    var element = document.getElementById(elementName);
    if (element) {
        element.innerText = elementValue;
    }
}

//
// Function: checkForAlarm()
// Determine whether the target has been reached, and if so, call the alarm hook.
//
function checkForAlarm()
{
    if (remainingSeconds <= 0) {
        // We're there
        if (isTargetReached == false) {
            // Make sure we only get here once
            isTargetReached = true;
            // Call the alarm hook
            if (doAction && zeroAction) {
                try {
                    eval(zeroAction);
                }
                catch (exception) {
                    alert(exception);
                }
            }
            // Start countdown to the next event
            setNextCountdownTarget();
        }
    }
}

//
// Function: updateCurrentTime()
// Store the current time in the clock's internal state
//
function updateCurrentTime()
{
    currentTime = new Date();
}

//
// Function: calculateRemainingTime()
// Determine the amount of time remaining to the current target
// Result is stored in the remainingTime array as [days, hours, minutes, seconds]
//
function calculateRemainingTime()
{
    // Start by getting the current date
    updateCurrentTime();
    // Clear excessive precision
    currentTime.setMilliseconds(0);

    // Number of seconds between now and target
    remainingSeconds = Math.floor((countdownTarget.eventTime.getTime() - currentTime.getTime()) / msPerSecond);
    if (remainingSeconds <= 0) {
        if (wantStopAtZero) {
            remainingSeconds = 0;
        }
        else {
            remainingSeconds = Math.abs(remainingSeconds);
        }
    }

    // Calculate days
    var remainingDays = Math.floor(remainingSeconds / sPerDay);
    // And take remainder
    var leftoverSeconds = remainingSeconds - remainingDays * sPerDay;
    // Same for hours, minutes, and seconds
    var remainingHours = Math.floor(leftoverSeconds / sPerHour);
    leftoverSeconds -= remainingHours * sPerHour;
    var remainingMinutes = Math.floor(leftoverSeconds / sPerMinute);
    leftoverSeconds -= remainingMinutes * sPerMinute;

    remainingTime[0] = remainingDays;
    remainingTime[1] = remainingHours;
    remainingTime[2] = remainingMinutes;
    remainingTime[3] = leftoverSeconds;
}

//
// Function: loadCalendarFromURL(callback, iCalURL)
// Loads and parses an iCal calendar
//
// callback: Optional function to be called after loading is complete
// iCalURL: URL of the iCal file to load
//
function loadCalendarFromURL(callback, iCalURL)
{
    var xmlRequest = new XMLHttpRequest();
    xmlRequest.open("GET", iCalURL, true);

    xmlRequest.onreadystatechange = function () {
        if (xmlRequest.readyState == 4) {
            iCalendar = (new ICSParser()).parse(xmlRequest.responseText);
            setNextCountdownTarget();
            // Fire the callback event, if necessary
            if (callback != null) {
                callback();
            }
        }
    };

    xmlRequest.send(null);
}

//
// Function: setCountdownTarget(countdownEvent)
// Set the timer's current event to count down to
//
// countdownEvent: object containing eventTime and eventLabel for the event
//
function setCountdownTarget(countdownEvent)
{
    countdownTarget = countdownEvent;
    if (countdownEvent.eventLabel != null) {
        setEventLabel(countdownEvent.eventLabel);
    }
}

//
// Function: setNextCountdownTarget()
// Find the next event in the event list and set the timer
//
function setNextCountdownTarget()
{
    updateCurrentTime();

    var nextEvent;
    if (iCalendar != null) {
        nextEvent = iCalendar[0].nextEvent();
    }

    if (nextEvent != null) {
        setCountdownTarget({ eventTime: nextEvent.nextOccurrence, eventLabel: nextEvent.summary });
        isTargetReached = false;
    }
}

//
// Function: startDisplayUpdateTimer()
// Start the interval timer to update the countdown once a second
//
function startDisplayUpdateTimer()
{
    updateTimerDisplay();

    if (!updateTimerDisplayInterval)
        updateTimerDisplayInterval = setInterval(updateTimerDisplay, 1000);
}

//
// Function: stopDisplayUpdateTimer()
// Remove the interval timer
//
function stopDisplayUpdateTimer()
{
    if (updateTimerDisplayInterval) {
        clearInterval(updateTimerDisplayInterval);
        updateTimerDisplayInterval = null;
    }
}

//
// Function: load()
// Called by HTML body element's onload event when the widget is ready to start
//
function load()
{
    dashcode.setupParts();

    // Get the properties
    targetKind         = +attributes.targetKind;
    wantLeadingZeros   = attributes.showLeadingZeros == 1;
    wantBlinkingColons = attributes.blinkSeparators == 1;
    wantStopAtZero     = attributes.reachedActionIndex == 0;
    doAction           = attributes.doAction == 1;
    zeroAction         = attributes.zeroAction;
    iCalEventSummary   = attributes.iCalEventSummary;
    sharediCalURL      = attributes.sharediCalURL;
    setCountdownTarget({ eventTime: attributes.targetDateTime,
                         eventLabel: null });

    // Fix up URL
    if (sharediCalURL && sharediCalURL.length) {
        sharediCalURL = sharediCalURL.replace(/^webcal:\/\//, "http://");
    }

    // Make sure the alarm will run if necessary
    isTargetReached = false;

    if (targetKind == 0) {
        // Just start the timer now
        startDisplayUpdateTimer();
    } else if (targetKind == 1 && iCalEventSummary) {
        // Single iCal event but still pointing to an URL
        loadCalendarFromURL(startDisplayUpdateTimer, iCalEventURL);
    } else if (targetKind == 2 && sharediCalURL) {
        // If we're using remote events, load them and use the callback to start the timer
        loadCalendarFromURL(startDisplayUpdateTimer, sharediCalURL);
    }
}

//
// Function: remove()
// Called when the widget has been removed from the Dashboard
//
function remove()
{
    // remove any preferences as needed
    // widget.setPreferenceForKey(null, dashcode.createInstancePreferenceKey("your-key"));
    stopDisplayUpdateTimer();
}

//
// Function: hide()
// Called when the widget has been hidden
//
function hide()
{
    // Stop timer to prevent CPU usage
    stopDisplayUpdateTimer();
}

//
// Function: show()
// Called when the widget has been shown
//
function show()
{
    // Re-start timer
    startDisplayUpdateTimer();
}

//
// Function: sync()
// Called when the widget has been synchronized with .Mac
//
function sync()
{
    // Retrieve any preference values that you need to be synchronized here
    // Use this for an instance key's value:
    // instancePreferenceValue = widget.preferenceForKey(null, dashcode.createInstancePreferenceKey("your-key"));
    //
    // Or this for global key's value:
    // globalPreferenceValue = widget.preferenceForKey(null, "your-key");
}

//
// Function: showBack(event)
// Called when the info button is clicked to show the back of the widget
//
// event: onClick event from the info button
//
function showBack(event)
{
    var front = document.getElementById("front");
    var back = document.getElementById("back");

    if (window.widget)
        widget.prepareForTransition("ToBack");

    front.style.display="none";
    back.style.display="block";

    if (window.widget)
        setTimeout('widget.performTransition();', 0);
}

//
// Function: showFront(event)
// Called when the done button is clicked from the back of the widget
//
// event: onClick event from the done button
//
function showFront(event)
{
    var front = document.getElementById("front");
    var back = document.getElementById("back");

    if (window.widget)
        widget.prepareForTransition("ToFront");

    front.style.display="block";
    back.style.display="none";

    //startDisplayUpdateTimer();

    if (window.widget)
        setTimeout('widget.performTransition();', 0);
}

// Initialize the Dashboard event handlers
if (window.widget) {
    widget.onremove = remove;
    widget.onhide = hide;
    widget.onshow = show;
    widget.onsync = sync;
}
