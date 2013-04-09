Feature: Availability reports
	Warning: Assumes the time format is ISO-8601 (the default)

	Background:
		Given I have these hostgroups configured:
			| hostgroup_name |
			| LinuxServers   |
			| WindowsServers |
			| MixedGroup     |
			| EmptyGroup     |
		And I have these hosts:
			| host_name      | host_groups               |
			| linux-server1  | LinuxServers,MixedGroup   |
			| linux-server2  | LinuxServers              |
			| win-server1    | WindowsServers            |
			| win-server2    | WindowsServers,MixedGroup |
		And I have these servicegroups:
			| servicegroup_name |
			| pings             |
			| empty             |
		And I have these services:
			| service_description | host_name     | check_command   | notifications_enabled | active_checks_enabled | service_groups |
			| System Load         | linux-server1 | check_nrpe!load | 1                     | 1                     |                |
			| PING                | linux-server1   | check_ping    | 1                     | 0                     | pings          |
			| System Load         | linux-server2 | check_nrpe!load | 1                     | 1                     |                |
			| PING                | win-server1   | check_ping      | 1                     | 0                     | pings          |
			| PING                | win-server2   | check_ping      | 0                     | 1                     | pings          |
		And I have these report data entries:
			| timestamp           | event_type | flags | attrib | host_name     | service_description | state | hard | retry | downtime_depth | output |
			| 2013-01-01 12:00:00 |        100 |  NULL |   NULL |               |                     |     0 |    0 |     0 |           NULL | NULL                |
			| 2013-01-01 12:00:01 |        801 |  NULL |   NULL | win-server1   |                     |     0 |    1 |     1 |           NULL | OK - laa-laa        |
			| 2013-01-01 12:00:02 |        801 |  NULL |   NULL | linux-server1 |                     |     0 |    1 |     1 |           NULL | OK - Sven Melander  |
			| 2013-01-01 12:00:03 |        701 |  NULL |   NULL | win-server1   | PING                |     0 |    1 |     1 |           NULL | OK - po             |
			| 2013-01-01 12:00:03 |        701 |  NULL |   NULL | win-server1   | PING                |     1 |    0 |     1 |           NULL | ERROR - tinky-winky |

		And I have activated the configuration

	# Technically doesn't need configuration, but the background will still trigger, and not having @configuration causes errors
	@configuration @asmonitor @calendar
	Scenario: Toggle JS-calendars on custom report date
		When I hover over the "Reporting" button
		And I click "Availability"
		And I select "Custom" from "Reporting period"

		And I click css "#cal_start"
		Then I should see css "#dp-popup"
		When I click css "#filter_field"
		Then I shouldn't see css "#dp-popup"

		When I click css "#cal_end"
		Then I should see css "#dp-popup"
		When I click css "#filter_field"
		Then I shouldn't see css "#dp-popup"

	@configuration @asmonitor
	Scenario: Generate report without objects
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I click "Show report"
		Then I should see "Please select what objects to base the report on"
		And I should see "Report Settings"

	@configuration @asmonitor
	Scenario: Generate report on empty hostgroup
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "EmptyGroup" from "Available Hostgroups"
		And I doubleclick "EmptyGroup" from "hostgroup_tmp[]"
		Then "Selected Hostgroups" should have option "EmptyGroup"
		And I click "Show report"
		Then I should see "No objects could be found in your selected groups to base the report on"
		And I should see "Report Settings"

	@configuration @asmonitor
	Scenario: Generate report on empty servicegroup
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "Servicegroups" from "Report type"
		And I select "empty" from "Available Servicegroups"
		And I doubleclick "empty" from "servicegroup_tmp[]"
		Then "Selected Servicegroups" should have option "empty"
		And I click "Show report"
		Then I should see "No objects could be found in your selected groups to base the report on"
		And I should see "Report Settings"

	@configuration @asmonitor
	Scenario: Generate single host report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "Hosts" from "Report type"
		And I select "linux-server1" from "Available Hosts"
		And I doubleclick "linux-server1" from "host_tmp[]"
		Then "Selected Hosts" should have option "linux-server1"
		And I click "Show report"
		Then I should see "Host details for linux-server1"
		And I should see "Selected services"
		And I shouldn't see "Selected hosts"
		And I should see "PING"
		And I shouldn't see "linux-server2"
		And I shouldn't see "win-server1"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate multi host report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "Hosts" from "Report type"
		And I select "linux-server1" from "Available Hosts"
		And I doubleclick "linux-server1" from "host_tmp[]"
		And I select "win-server1" from "Available Hosts"
		And I doubleclick "win-server1" from "host_tmp[]"
		Then "Selected Hosts" should have option "linux-server1"
		And "Selected Hosts" should have option "win-server1"
		And I click "Show report"
		Then I should see "Host state breakdown"
		And I should see "Selected hosts"
		And I shouldn't see "Selected services"
		And I should see "linux-server1"
		And I should see "win-server1"
		And I shouldn't see "linux-server2"
		And I shouldn't see "win-server2"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate single service report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "Services" from "Report type"
		And I select "linux-server1;PING" from "Available Services"
		And I doubleclick "linux-server1;PING" from "service_tmp[]"
		Then "Selected Services" should have option "linux-server1;PING"
		And I click "Show report"
		Then I should see "Service details for PING on host linux-server1"
		And I shouldn't see "System Load"
		And I shouldn't see "win-server"
		And I shouldn't see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate multi service on same host report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "Services" from "Report type"
		And I select "linux-server1;PING" from "Available Services"
		And I doubleclick "linux-server1;PING" from "service_tmp[]"
		And I select "linux-server1;System Load" from "Available Services"
		And I doubleclick "linux-server1;System Load" from "service_tmp[]"
		Then "Selected Services" should have option "linux-server1;PING"
		And "Selected Services" should have option "linux-server1;System Load"
		And I click "Show report"
		Then I should see "Service state breakdown"
		And I should see "Services on host: linux-server1"
		And I should see "PING"
		And I should see "System Load"
		And I shouldn't see "linux-server2"
		And I shouldn't see "win-server1"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate multi service on different host report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "Services" from "Report type"
		And I select "linux-server1;PING" from "Available Services"
		And I doubleclick "linux-server1;PING" from "service_tmp[]"
		And I select "linux-server2;System Load" from "Available Services"
		And I doubleclick "linux-server2;System Load" from "service_tmp[]"
		Then "Selected Services" should have option "linux-server1;PING"
		And "Selected Services" should have option "linux-server2;System Load"
		And I click "Show report"
		Then I should see "Service state breakdown"
		And I should see "Services on host: linux-server1"
		And I should see "PING"
		And I should see "Services on host: linux-server2"
		And I should see "System Load"
		And I shouldn't see "win-server"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate single hostgroup report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "LinuxServers" from "Available Hostgroups"
		And I doubleclick "LinuxServers" from "hostgroup_tmp[]"
		Then "Selected Hostgroups" should have option "LinuxServers"
		And I click "Show report"
		Then I should see "Hostgroup breakdown"
		And I should see "LinuxServers"
		And I should see "linux-server1"
		And I should see "linux-server2"
		And I shouldn't see "win-server1"
		And I shouldn't see "win-server2"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate multi hostgroup report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "LinuxServers" from "Available Hostgroups"
		And I doubleclick "LinuxServers" from "hostgroup_tmp[]"
		And I select "WindowsServers" from "Available Hostgroups"
		And I doubleclick "WindowsServers" from "hostgroup_tmp[]"
		Then "Selected Hostgroups" should have option "LinuxServers"
		And "Selected Hostgroups" should have option "WindowsServers"
		And I click "Show report"
		Then I should see "Hostgroup breakdown"
		And I should see "Average and Group availability for Hostgroup: LinuxServers"
		And I should see "Average and Group availability for Hostgroup: WindowsServers"
		And I should see "linux-server1"
		And I should see "linux-server2"
		And I should see "win-server1"
		And I should see "win-server2"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate hostgroup report with overlapping members
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "LinuxServers" from "Available Hostgroups"
		And I doubleclick "LinuxServers" from "hostgroup_tmp[]"
		And I select "MixedGroup" from "Available Hostgroups"
		And I doubleclick "MixedGroup" from "hostgroup_tmp[]"
		Then "Selected Hostgroups" should have option "LinuxServers"
		And "Selected Hostgroups" should have option "MixedGroup"
		When I click "Show report"
		Then I should see "Hostgroup breakdown"
		And I should see "Average and Group availability for Hostgroup: LinuxServers"
		And I should see "Average and Group availability for Hostgroup: MixedGroup"
		And I should see "linux-server1"
		And I should see "linux-server2"
		And I shouldn't see "win-server1"
		And I should see "win-server2"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate single servicegroup report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "Servicegroups" from "Report type"
		And I select "pings" from "Available Servicegroups"
		And I doubleclick "pings" from "servicegroup_tmp[]"
		Then "Selected Servicegroups" should have option "pings"
		When I click "Show report"
		Then I should see "Servicegroup breakdown"
		And I should see "pings"
		And I should see "Services on host: linux-server1"
		And I should see "Services on host: win-server1"
		And I should see "Services on host: win-server2"
		And I should see "PING"
		And I shouldn't see "linux-server2"
		And I shouldn't see "System Load"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate multi servicegroup report
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "Servicegroups" from "Report type"
		And I select "pings" from "Available Servicegroups"
		And I doubleclick "pings" from "servicegroup_tmp[]"
		And I select "empty" from "Available Servicegroups"
		And I doubleclick "empty" from "servicegroup_tmp[]"
		Then "Selected Servicegroups" should have option "pings"
		And "Selected Servicegroups" should have option "empty"
		When I click "Show report"
		Then I should see "Servicegroup breakdown"
		And I should see "Average and Group availability for Servicegroup: pings"
		And I shouldn't see "Average and Group availability for Servicegroup: empty"
		And I should see "Services on host: linux-server1"
		And I should see "Services on host: win-server1"
		And I should see "Services on host: win-server2"
		And I should see "PING"
		And I shouldn't see "linux-server2"
		And I shouldn't see "System Load"
		And I should see "Group availability (SLA)"

	@configuration @asmonitor
	Scenario: Generate report on custom report date
		Given I am on the Host details page
		And I hover over the "Reporting" button
		When I click "Availability"
		And I select "LinuxServers" from "Available Hostgroups"
		And I doubleclick "LinuxServers" from "hostgroup_tmp[]"
		Then "Selected Hostgroups" should have option "LinuxServers"
		When I select "Custom" from "Reporting period"
		And I enter "2013-01-02" into "Start date"
		And I enter "23:31" into "time_start"
		And I enter "2013-04-03" into "End date"
		And I enter "22:32" into "time_end"
		And I select "workhours" from "Report time period"
		When I click "Show report"
		Then I should see "Hostgroup breakdown"
		And I should see "Reporting period: 2013-01-02 23:31:00 to 2013-04-03 22:32:00 - workhours"
