<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Calendar</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="/favicon.ico"/>

    <!-- CALENDAR -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css" />
    <link rel="stylesheet" href="css/style.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/locales-all.min.js"></script>
	<script src="https://apis.google.com/js/api.js"></script>

   <script>
		$(document).ready(function(){
			var calendar = document.getElementById('calendar');
			var calendar = new FullCalendar.Calendar(calendar, {
				googleCalendarApiKey: 'AIzaSyD2JqmNcrJyx6Ll_xQHsxCVHQCe0s1QHZs',
				editable: true,
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay,listYear'
				},
				eventSources: [
					// "<?php echo base_url(); ?>/load",
					{
						googleCalendarId: '1gd18sauhrs58dcuq9718uisn0@group.calendar.google.com',
					}
				],
				selectable:true,
				select:function(arg)
				{
					var title = prompt("Enter Event Title");

					if(title)
					{
						calendar.addEvent({
							title: title,
							start: arg.startStr,
							end: arg.endStr,
							// allDay: arg.allDay
						})

						if (arg.allDay == true) {
							start = arg.startStr+"T00:00:00+02:00"
							end = arg.startStr+"T23:59:00+02:00"
						} else {
							start = arg.startStr
							end = arg.endStr
						}

						gapi.client.calendar.events.insert({
							"calendarId": "1gd18sauhrs58dcuq9718uisn0@group.calendar.google.com",
									"resource": {
										"summary": title,
										"start": {
										"dateTime": start
										},
										"end": {
										"dateTime": end
										},
									}
									
								})
								.then(function(response) {
								// Handle the results here (response.result has the parsed body).
									$.ajax({
										url:"<?php echo base_url(); ?>",
										type:"POST",
										data:{title:title, start:arg.startStr, end:arg.endStr, eventId: response.result.id},
										success:function()
										{
											alert("Added Successfully");
										}
									})
								},
								function(err) { console.error("Execute error", err); });
					} 
					calendar.unselect()
				},
				editable:true,
				eventResize:function(arg)
				{
					if (arg.allDay == true) {
						start = arg.event.startStr+"T00:00:00+02:00"
						end = arg.event.startStr+"T23:59:00+02:00"
					} else {
						start = arg.event.startStr
						end = arg.event.endStr
					}
					gapi.client.calendar.events.update({
						'calendarId': '1gd18sauhrs58dcuq9718uisn0@group.calendar.google.com',
						'eventId': arg.event.id, 
						"resource": {
							"summary": arg.oldEvent.title,
							"start": {
							"dateTime": start
							},
							"end": {
							"dateTime": end
							}
						}
					}).then(function(response) {
						console.log(response)
					});

					$.ajax({
						url:"<?php echo base_url(); ?>",
						type:"PUT",
						data:{title: arg.oldEvent.title, start:arg.event.startStr, end:arg.event.endStr, id: arg.oldEvent.id},
						success:function()
						{
							alert("Event Update");
						}
					})
				},
				eventDrop:function(arg)
				{
					if (arg.allDay == true) {
						start = arg.event.startStr+"T00:00:00+02:00"
						end = arg.event.startStr+"T23:59:00+02:00"
					} else {
						start = arg.event.startStr
						end = arg.event.endStr
					}
					gapi.client.calendar.events.update({
						'calendarId': '1gd18sauhrs58dcuq9718uisn0@group.calendar.google.com',
						'eventId': arg.event.id, 
						"resource": {
							"summary": arg.oldEvent.title,
							"start": {
							"dateTime": start
							},
							"end": {
							"dateTime": end
							}
						}
					}).then(function(response) {
						console.log(response)
					});
					$.ajax({
						url:"<?php echo base_url(); ?>",
						type:"PUT",
						data:{title:arg.oldEvent.title, start:arg.event.startStr, end:arg.event.endStr, id:arg.event.id},
						success:function()
						{
							alert("Event Updated");
						}
					})
				},
				eventClick:function(arg)
				{
					if(confirm("Are you sure you want to remove it?"))
					{
						gapi.client.calendar.events.delete({
							'calendarId': '1gd18sauhrs58dcuq9718uisn0@group.calendar.google.com',
							'eventId': arg.event.id, 
						}).then(function(response) {
		
						});
							$.ajax({
								url:"<?php echo base_url(); ?>",
								type:"DELETE",
								data:{id:arg.event.id},
								success:function()
								{
									arg.event.remove()
									alert('Event Removed');
								}
							})
					}
				}
			});
    		calendar.render();
		});
   </script>

</head>
<body>
	<!-- HEADER: MENU + HEROE SECTION -->
	<header>
		<div class="menu">
			<h1>My Calendar</h1>
			<!--Add buttons to initiate auth sequence and sign out-->
			<button id="authorize_button" style="display: none;">Authorize</button>
			<button id="signout_button" style="display: none;">Sign Out</button>
			<pre id="content" style="white-space: pre-wrap;"></pre>
		</div>
	</header>

    <script type="text/javascript">
      // Client ID and API key from the Developer Console
      var CLIENT_ID = '653530328132-8j32gspv882nva2qv1ab0v50mfscg17l.apps.googleusercontent.com';
      var API_KEY = 'AIzaSyD2JqmNcrJyx6Ll_xQHsxCVHQCe0s1QHZs';

      // Array of API discovery doc URLs for APIs used by the quickstart
      var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

      // Authorization scopes required by the API; multiple scopes can be
      // included, separated by spaces.
      var SCOPES = "https://www.googleapis.com/auth/calendar.readonly";

      var authorizeButton = document.getElementById('authorize_button');
      var signoutButton = document.getElementById('signout_button');

      /**
       *  On load, called to load the auth2 library and API client library.
       */
      function handleClientLoad() {
        gapi.load('client:auth2', initClient);
      }

      /**
       *  Initializes the API client library and sets up sign-in state
       *  listeners.
       */
      function initClient() {
        gapi.client.init({
          apiKey: API_KEY,
          clientId: CLIENT_ID,
          discoveryDocs: DISCOVERY_DOCS,
          scope: SCOPES
        }).then(function () {
          // Listen for sign-in state changes.
          gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);

          // Handle the initial sign-in state.
          updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
          authorizeButton.onclick = handleAuthClick;
          signoutButton.onclick = handleSignoutClick;
        }, function(error) {
          appendPre(JSON.stringify(error, null, 2));
        });
      }

      /**
       *  Called when the signed in status changes, to update the UI
       *  appropriately. After a sign-in, the API is called.
       */
      function updateSigninStatus(isSignedIn) {
        if (isSignedIn) {
          authorizeButton.style.display = 'none';
          signoutButton.style.display = 'block';
          listUpcomingEvents();
        } else {
          authorizeButton.style.display = 'block';
          signoutButton.style.display = 'none';
        }
      }

      /**
       *  Sign in the user upon button click.
       */
      function handleAuthClick(event) {
        gapi.auth2.getAuthInstance().signIn();
      }

      /**
       *  Sign out the user upon button click.
       */
      function handleSignoutClick(event) {
        gapi.auth2.getAuthInstance().signOut();
      }

      /**
       * Append a pre element to the body containing the given message
       * as its text node. Used to display the results of the API call.
       *
       * @param {string} message Text to be placed in pre element.
       */
      function appendPre(message) {
        var pre = document.getElementById('content');
        var textContent = document.createTextNode(message + '\n');
        pre.appendChild(textContent);
      }

      /**
       * Print the summary and start datetime/date of the next ten events in
       * the authorized user's calendar. If no events are found an
       * appropriate message is printed.
       */
      function listUpcomingEvents() {
        gapi.client.calendar.events.list({
          'calendarId': '1gd18sauhrs58dcuq9718uisn0@group.calendar.google.com',
          'timeMin': (new Date()).toISOString(),
          'showDeleted': false,
          'singleEvents': true,
          'maxResults': 10,
          'orderBy': 'startTime'
        }).then(function(response) {
          var events = response.result.items;
		$.ajax({
			url:"<?php echo base_url(); ?>",
			type:"DELETE",
			data:{events: events},
			success:function(response)
			{
				console.log(response)
			}
		})
          appendPre('Upcoming events:');

          if (events.length > 0) {
            for (i = 0; i < events.length; i++) {
              var event = events[i];
              var when = event.start.dateTime;
              if (!when) {
                when = event.start.date;
              }
              appendPre(event.summary + ' (' + when + ')')
            }
          } else {
            appendPre('No upcoming events found.');
          }
        });
      }

    </script>

    <script async defer src="https://apis.google.com/js/api.js"
    	onload="this.onload=function(){};handleClientLoad()"
      	onreadystatechange="if (this.readyState === 'complete') this.onload()">
    </script>

<!-- CONTENT -->
<section>

<div class=" mt-3" id='calendar'></div>
</section>

<div class="further">

	<section>

	</section>

</div>

<!-- FOOTER: DEBUG INFO + COPYRIGHTS -->

<footer>
	<div class="environment">

		<!-- <p>Page rendered in {elapsed_time} seconds</p>

		<p>Environment: <?= ENVIRONMENT ?></p> -->

	</div>

	<div class="copyrights">

		<p>&copy; <?= date('Y') ?> CodeIgniter Foundation. CodeIgniter is open source project released under the MIT
			open source licence.</p>

	</div>
</footer>

</body>
</html>
