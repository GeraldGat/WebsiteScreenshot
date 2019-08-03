# WebsiteScreenshot
Quick exercise of an application being able to take screenshot of website pushing it to your google drive.
## Getting started
### Installation
First download or [clone](https://help.github.com/en/articles/cloning-a-repository) this repository.
### Prerequisites 
This project run on PHP 7.1.

This project can't run on PHP 7.2 because of a problem with [guzzle](http://docs.guzzlephp.org/en/stable/).
### Download dependencies 
To download all dependencies, go to the project root and run:
```
php composer.phar install
```
### Run the project
You can now run the project by doing:
```
php bin/console server:run
```
### Configure the project
Go to http://localhost:8000/config to configure the project.
#### Google API
##### Making a new Google APIs project
First, [create a new project on the Google APIs console](https://console.developers.google.com/projectcreate?previousPage=%2Fprojectselector2%2Fapis%2Fdashboard%3Fhl%3DFR%26pli%3D1%26supportedpurview%3Dproject&hl=EN&authuser=1&project=websitescreensho-1564591033839&folder=&organizationId=).
##### Enable Google drive API
Go to the [Google drive API page](https://console.developers.google.com/apis/library/drive.googleapis.com), choose your project and enable the API.
##### Configure OAuth consent screen
In the left menu, go to credentials and now go to "AOuth consent screen". Set an application name and save.
##### Create credentials
Now, create a "OAuth Client ID" credentials for a Web application. Set the name of the OAuth Client ID and add http://localhost:8000 to "Authorised JavaScript origins" and http://localhost:8000/screen to "Authorised redirect URIs".
##### Set your Google Drive API configuration
You can now go to http://localhost/config and register your Client Id and Client secret. The redirect uri should be http://localhost:8000/screen for you and scopes should be https://www.googleapis.com/auth/drive.file but was here for development purposes.
##### Configure the "parents"
The parents are the folders id of your google drive. It can be obtained by going in your folder and obtain it from the url : https://drive.google.com/drive/u/1/folders/[yourId]
#### Screenshot Machine
To obtain your screenshot machine API key, go to https://www.screenshotmachine.com/ and sign up, you now have your "Customer key".
#### Websites
For the websites part, insert a name that will be used for the image on google drive, the url of the website you want a screenshot and check if you want the program to add the date of the screen into the name of the image.
### Take screenshot !
When every configuration was made, you can save your change and now click on the "Make screen" button to take screen of the different website registered.
