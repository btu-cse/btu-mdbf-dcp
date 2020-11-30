=== UniTimetable ===
Contributors: antrouss, kokkoras
Donate link: -
Tags: timetable, calendar, lectures
Requires at least: 4.0
Tested up to: 4.3
Stable tag: 1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

UniTimetable creates timetables for educational institutes. Display lecturing and usage timetables for teachers, semesters, labs, etc.

== Description ==
**UniTimetable** is a WordPress plugin for presenting timetables of an educational institute. It includes teachers, classrooms, subjects (modules) and student groups, which are all combined to define lectures. The lectures can be scheduled at some time point during a semester. Out of schedule events and holidays are also supported.
After providing the plugin with data, shortcodes provided generate beautiful calendars with all or selected part of the entered data.

UniTimetable was designed by **Fotis Kokkoras** and **Antonis Roussos** and implemented by Antonis Roussos for the fulfillment of his BSc Thesis in the **Department of Computer Science and Engineering**, at **TEI of Thessaly**, Greece.


== Installation ==
1. Upload the entire UniTimetable folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Find `UniTimeTable` menu in your WordPress admin panel and use the forms provided for data entry.

How to use the Shortcodes:

The general purpose shortcode is `[utt_calendar]` and should be better placed in a page (or post) with substantial width (the calendar is responsive though and fits everywhere). The resulting calendar includes two filter drop-down lists for the end-user to select individual calendars for any of the semester, classroom, or teacher.
In case that a fixed calendar is required (without drop down filters), parameters can be added to precisely define the content to be displayed. Three parameters are supported: classroom, teacher and semester. Here are some examples:

* `[utt_calendar classroom=1]` will display the schedule of classroom with ID=1.
* `[utt_calendar classroom=1,2,3]` will display the schedule of classrooms with IDs 1, 2 and 3.
* `[utt_calendar teacher = 12]` will display the lecturing program of teacher with ID 12.
* `[utt_calendar semester = 2]` will display the lecturing program of the semester with ID 2.

**Note:** *You can locate the IDs required in the corresponding forms in the UnitimeTable admin page. Comma separated IDs are supported in all cases.*


== Screenshots ==
1. Resulting timetable with filtering drop-down lists (the example data are in Greek).
2. The main admin page of the plugin.
3. Form for managing subjects (the example data are in Greek).
4. Lecture management form. You can create multiple entries for successive weeks with a single insert, by defining the `number of weeks` value (for example, if you want to schedule Algebra for the next 13 weeks).
5. The calendar as seen from the lecture management form (the example data are in Greek). Note the delete and edit buttons in each entry.


== Changelog ==

= 1.1 =
*Release Date - 26 October 2015*

* Allow commas on Holiday Name, in case of two simultaneous Holidays.
* Spelling mistakes were corrected.
* Corrected Practise Exercises which were shown like Laboratories.
* Fixed problem with Lectures deletion and edit.

= 1.0 =
*Release Date - 21 August 2015*

Initial Release.


== Translations ==
* English - default, always included
* Greek - default, always included

*Note:* Translations refer to the user interface (admin panel and calendar). All the content will be always displayed in the language inserted. You can not translate a timetable. If desired so, the only way is to build a new calendar in another language.

