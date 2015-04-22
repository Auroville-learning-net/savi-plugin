Some notes for developpers on this plugin,

It controls the Savi Admin tool.  Currently as of April 2015, 8 views are created in the Dashboard under the Enquiries section.

Views 0 to 7 are controlled by the php files View_0-...php to View_7....php.

Each of these extend the Base_Views-Default_Profile.php class.

With the except of View_0, they all represent the same custom post structure.  The meta fields are created in the Base_Views...php

Each custom post manages a profile of a volunteers are different process step.

Action in the Dashboard tool move each profile post from its current view_X to the next view_X.

View_0 = initial enquiry made by a volunteer
View_1 = after aproval, the volunteer is given an account (user name + password) and is requested to fill in their detailed profile.
View_2 = when the profile form has been filled and approved, the volunteer can select opportunities.  The admin team can also add opportunities to a given profile.
View_3 = the volunteers is requested to select their preferred opportunity, upon which the mentors are requested to also select their preferred volunteer.
View_4 = in case there is no response from a volunteer, their profile is shifted to this dormant view after 3 reminders
View_5 = For those requiring a VISA, their profile is moved to this view after view_3
View_6 = When VISA application is in progress and confirmation is pending, the profile is in this view
View_7 = the volunteer has a VISA (or does not require one) and are now about to arrive to Auroville. This is the last view before they arrive.

//TODO
View_8 = a view for volunteer profiles currently in Auroville
View_9 = an archive of volunteer profiles who have left Auroville.

Actions are controlled in the custom-bulk-action.php file.

Auroville-Units.php - controls the Units post section
AVProjects.php - controls the Projects post section
Guest-House.php - controls the guest houses post section
Research.php - controls the research post section
Opportunities.php - controls the opportunities post section
class_listing.php - a listings custom post that integrates google map functionality into the post that extend it.  For examples, Units and Guest Houses extend this post, allowing them to be located on a google map.
Programs.php - some ongoing work
Custom_Views_column.php - this is used to manage the columns being displayed in each view summary table