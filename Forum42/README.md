**************************************************************************************************************************************************************************
__/\\\\\\\\\\\\\\\_______/\\\\\_________/\\\\\\\\\______/\\\________/\\\__/\\\\____________/\\\\______________________/\\\_______/\\\\\\\\\_____                     
__\/\\\///////////______/\\\///\\\_____/\\\///////\\\___\/\\\_______\/\\\_\/\\\\\\________/\\\\\\____________________/\\\\\_____/\\\///////\\\___                    
___\/\\\_______________/\\\/__\///\\\__\/\\\_____\/\\\___\/\\\_______\/\\\_\/\\\//\\\____/\\\//\\\__________________/\\\/\\\____\///______\//\\\__                   
____\/\\\\\\\\\\\______/\\\______\//\\\_\/\\\\\\\\\\\/____\/\\\_______\/\\\_\/\\\\///\\\/\\\/_\/\\\________________/\\\/\/\\\______________/\\\/___                  
_____\/\\\///////______\/\\\_______\/\\\_\/\\\//////\\\____\/\\\_______\/\\\_\/\\\__\///\\\/___\/\\\______________/\\\/__\/\\\___________/\\\//_____                 
______\/\\\_____________\//\\\______/\\\__\/\\\____\//\\\___\/\\\_______\/\\\_\/\\\____\///_____\/\\\____________/\\\\\\\\\\\\\\\\_____/\\\//________                
_______\/\\\______________\///\\\__/\\\____\/\\\_____\//\\\__\//\\\______/\\\__\/\\\_____________\/\\\___________\///////////\\\//____/\\\/___________               
________\/\\\________________\///\\\\\/_____\/\\\______\//\\\__\///\\\\\\\\\/___\/\\\_____________\/\\\_____________________\/\\\_____/\\\\\\\\\\\\\\\_              
_________\///___________________\/////_______\///________\///_____\/////////_____\///______________\///______________________\///_____\///////////////__             
 ________________________/\\\\\\\\\______/\\\\\\\\\\\\\\\_____/\\\\\\\\\_____/\\\\\\\\\\\\_____/\\\\____________/\\\\__/\\\\\\\\\\\\\\\__________________         
  ______________________/\\\///////\\\___\/\\\///////////____/\\\\\\\\\\\\\__\/\\\////////\\\__\/\\\\\\________/\\\\\\_\/\\\///////////___________________        
   _____________________\/\\\_____\/\\\___\/\\\______________/\\\/////////\\\_\/\\\______\//\\\_\/\\\//\\\____/\\\//\\\_\/\\\______________________________       
    _____________________\/\\\\\\\\\\\/____\/\\\\\\\\\\\_____\/\\\_______\/\\\_\/\\\_______\/\\\_\/\\\\///\\\/\\\/_\/\\\_\/\\\\\\\\\\\______________________      
     _____________________\/\\\//////\\\____\/\\\///////______\/\\\\\\\\\\\\\\\_\/\\\_______\/\\\_\/\\\__\///\\\/___\/\\\_\/\\\///////_______________________     
      _____________________\/\\\____\//\\\___\/\\\_____________\/\\\/////////\\\_\/\\\_______\/\\\_\/\\\____\///_____\/\\\_\/\\\______________________________    
       _____________________\/\\\_____\//\\\__\/\\\_____________\/\\\_______\/\\\_\/\\\_______/\\\__\/\\\_____________\/\\\_\/\\\______________________________   
        _____________________\/\\\______\//\\\_\/\\\\\\\\\\\\\\\_\/\\\_______\/\\\_\/\\\\\\\\\\\\/___\/\\\_____________\/\\\_\/\\\\\\\\\\\\\\\__________________  
         _____________________\///________\///__\///////////////__\///________\///__\////////////_____\///______________\///__\///////////////___________________

**************************************************************************************************************************************************************************
*
*   This was a project that was started on February 17th 2019 and completed on May 8th 2019. The purpose of this project was to experiment and learn about the uses
*   of PHP and how it handles processing data. The intent was to create a php web base API that would handle all back end processes. The front end would then call in to 
*   the web API to get data and display the information to the end user. Throughout this project it allowed me to find the uses and downfalls of the PHP language and 
*   test theories behind object oriented design in the processes.
*   The end result was a de-coupled system that that handles all processes separately and uses a RESTy type layer to handle all data processes. This was an attempt at a 
*   single page application type framework that enhanced my concepts about what the best way to handle data across the full stack of the developmental process. After
*   the project was completed I see how I misused some of the developmental concepts and bastardized some of the concepts behind what a MVC is supposed to be, so some
*   of the naming conventions may be slightly off in that respect and I apologize.
*
**************************************************************************************************************************************************************************
*
*   Below, I will describe how each process works together and the difficulties that I encountered in trying to achieve these processes.
*
**************************************************************************************************************************************************************************
*
*   FLOW:
*   The flow of each process works as so.
*   - End user enters url to get to web page.
*   - router.php handles the URI request and loads the correct web page in the view.
*   - End user does action on page. ( three possible outcomes )
*       - user is redirected to another page.
*           - this completes the action.            
*       - user make and AJAX request via JavaScript
*           - the "onclick" function calls into a function in JavaScript then returns a Promise.
*           - the request is sent a controller in the rest/ajax folder that send a request to the SVC layer
*           - the request initiates a series of functions in the SVC layer that are processed in the database and returned as a ResponseModel
*           - the API receives this response and writes it to the page that resolves the promise from the front end layer
*           - if the response object returns a success on the call we return that to the view otherwise there is no change in the data.
*           *** NOTE: this is what I was trying to achieve with the API/rest layer for all calls but found that PHP handles data differently in this respect
*       - user makes a POST request via PHP
*           - the form is processed as a POST request via a PHP form from the view
*           - the view reviews the POST request and determines if it for that particular request (this is because there are multiple forms on a page)
*           - the view calls into the front end Controller and sets all data needed for the API
*           - the front end Controller then passes all data to the rest layer router that then calls into the corresponding function
*           - the rest layer router then builds up all corresponding data that is then passed to the svc layer.
*           - the svc or dao layer handles the request and wraps it's data into a ResponseObject that is then passed back to the rest layer.
*           - the rest layer acts as the handshake and prints the response to the page that is then retrieved by the front end layer and displayed to the user
*           *** NOTE: this is what a majority of the calls look like as far as flow is concerned
*           
*
*
*
*
*
*
*
*
*

