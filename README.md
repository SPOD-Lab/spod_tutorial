#SPOD Tutorial
This plugin introduce gamified aspect into SPOD
##What you get
Plugin is composed by a widget on Index and on User Profile and two utility classes.
##How it works
This plugin check an extendable list (19 actually) of challenges proposed to the users and every day two random challenge are assigned to every user.
To introduce new challenges simply add them into the database, respecting the format:
```
id, title, body, dependencies
```
where:
* id one more bigger than the biggest id already in database
* title and body are language key that should be added to the language system of SPOD
* dependencies, also null, are the id of priority challenge on the considered one

##Plugin Dependencies
This plugins uses features of other plugins that must be installed. The required Plugins are:
* Newsfeed
* SPOD Public Room
* SPOD Private Room
* SPOD CoCreation


###Known issues
The component loaded by detail.php for plugin CoCreation, Agorà and MySpace are shown with wrong aspect