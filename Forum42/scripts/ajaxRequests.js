// this is the base request function creating a promise 
// to allow for ajax requests
function makeRequest(opts) {
    return new Promise(function (resolve, reject) {
      var xhr = new XMLHttpRequest();
      xhr.open(opts.method, opts.url);
      xhr.onload = function () {
        if (this.status >= 200 && this.status < 300) {
          resolve(xhr.response);
        } else {
          reject({
            status: this.status,
            statusText: xhr.statusText
          });
        }
      };
      xhr.onerror = function () {
        reject({
          status: this.status,
          statusText: xhr.statusText
        });
      };
      if (opts.headers) {
        Object.keys(opts.headers).forEach(function (key) {
          xhr.setRequestHeader(key, opts.headers[key]);
        });
      }
      var params = opts.params;
      if (params && typeof params === 'object') {
        params = Object.keys(params).map(function (key) {
          return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]);
        }).join('&');
      }
      xhr.send(params);
    });
  }
// this makes the call for users to logout 
  function logout(){
    makeRequest({
      method: 'POST',
      url: './controllers/logoutController.php',
      params: {
        logout: true
      },
      headers: {
        'Content-type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json'
      }
    }).then(function() {
      if((window.location.href.indexOf("manage-account") > -1)){
        location.replace('/Forum42/');
      }
      else{
        var meta = document.createElement('meta');
        meta.httpEquiv = "refresh";
        meta.content = "0";
        document.getElementsByTagName('head')[0].appendChild(meta);
      }
      
    })
    .catch(function (err) {
      console.error('error: ', err.statusText);
    });
  }

  // this makes the call to update an vote on threads
  function higlightArrow(getArrow){
    var arrow = document.getElementById(getArrow);
    var url = "./controllers/updateThreadPointsController.php";
    if(getArrow.includes("updoot")){
      var onePoint = true;
      var threadId = getArrow.replace("updoot","");
      var getPoints = getArrow.replace("updoot","points");
      var points = document.getElementById(getPoints);
      var getdownArrow = getArrow.replace("updoot","downdoot");
      var downArrow = document.getElementById(getdownArrow);
      arrow.classList.toggle("upvote");
      arrow.toggleAttribute('data-voted');
      if(downArrow.getAttribute('data-voted') == '' && arrow.getAttribute('data-voted') != null){
        downArrow.classList.toggle("downvote");
        downArrow.toggleAttribute('data-voted');
        score = parseInt(points.innerHTML) + 2;
        var payload = {
          id: threadId,
          points: 2
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            points.innerHTML = score.toString();
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
        onePoint = false;
      }
      if(arrow.getAttribute('data-voted') != null && onePoint) {
        score = parseInt(points.innerHTML) + 1;
        var payload = {
          id: threadId,
          points: 1
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            points.innerHTML = score.toString();
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
      }
      if(arrow.getAttribute('data-voted') == null){
        score = parseInt(points.innerHTML) - 1;
        var payload = {
          id: threadId,
          points: -1
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            points.innerHTML = score.toString();
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
      }
    }
    if(getArrow.includes("downdoot")){
      var onePoint = true;
      var threadId = getArrow.replace("downdoot","");
      var getPoints = getArrow.replace("downdoot","points");
      var points = document.getElementById(getPoints);
      var getUpArrow = getArrow.replace("downdoot","updoot");
      var upArrow = document.getElementById(getUpArrow);
      arrow.classList.toggle("downvote");
      arrow.toggleAttribute('data-voted');
      if(upArrow.getAttribute('data-voted') == '' && arrow.getAttribute('data-voted') != null){
        upArrow.classList.toggle("upvote");
        upArrow.toggleAttribute('data-voted');
        score = parseInt(points.innerHTML) - 2;
        var payload = {
          id: threadId,
          points: -2
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            points.innerHTML = score.toString();
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
        onePoint = false;
      }
      if(arrow.getAttribute('data-voted') != null && onePoint){
        score = parseInt(points.innerHTML) - 1;
        var payload = {
          id: threadId,
          points: -1
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            points.innerHTML = score.toString();
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
      }
      if(arrow.getAttribute('data-voted') == null){
        score = parseInt(points.innerHTML) + 1;
        var payload = {
          id: threadId,
          points: 1
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            points.innerHTML = score.toString();
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
      }
    }
  }
  
  // this updates comment votes
  function higlightCommentArrow(getArrow){
    var arrow = document.getElementById(getArrow);
    var url = "./controllers/updateCommentPointsController.php";
    if(getArrow.includes("updoot")){
      var onePoint = true;
      var commentId = getArrow.replace("updoot","");
      var getPoints = getArrow.replace("updoot","points");
      var points = document.getElementById(getPoints);
      var getdownArrow = getArrow.replace("updoot","downdoot");
      var downArrow = document.getElementById(getdownArrow);
      arrow.classList.toggle("upvote");
      arrow.toggleAttribute('data-voted');
      if(downArrow.getAttribute('data-voted') == '' && arrow.getAttribute('data-voted') != null){
        downArrow.classList.toggle("downvote");
        downArrow.toggleAttribute('data-voted');
        score = parseInt(points.innerHTML) + 2;
        var payload = {
          id: commentId,
          points: 2
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            if(score == 1){
              points.innerHTML = score.toString() + " point";
            }
            else{
              points.innerHTML = score.toString() + " points";
            }
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
        onePoint = false;
      }
      if(arrow.getAttribute('data-voted') != null && onePoint) {
        score = parseInt(points.innerHTML) + 1;
        var payload = {
          id: commentId,
          points: 1
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            if(score == 1){
              points.innerHTML = score.toString() + " point";
            }
            else{
              points.innerHTML = score.toString() + " points";
            }
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
      }
      if(arrow.getAttribute('data-voted') == null){
        score = parseInt(points.innerHTML) - 1;
        var payload = {
          id: commentId,
          points: -1
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            if(score == 1){
              points.innerHTML = score.toString() + " point";
            }
            else{
              points.innerHTML = score.toString() + " points";
            }
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
      }
    }
    if(getArrow.includes("downdoot")){
      var onePoint = true;
      var commentId = getArrow.replace("downdoot","");
      var getPoints = getArrow.replace("downdoot","points");
      var points = document.getElementById(getPoints);
      var getUpArrow = getArrow.replace("downdoot","updoot");
      var upArrow = document.getElementById(getUpArrow);
      arrow.classList.toggle("downvote");
      arrow.toggleAttribute('data-voted');
      if(upArrow.getAttribute('data-voted') == '' && arrow.getAttribute('data-voted') != null){
        upArrow.classList.toggle("upvote");
        upArrow.toggleAttribute('data-voted');
        score = parseInt(points.innerHTML) - 2;
        var payload = {
          id: commentId,
          points: -2
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            if(score == 1){
              points.innerHTML = score.toString() + " point";
            }
            else{
              points.innerHTML = score.toString() + " points";
            }
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
        onePoint = false;
      }
      if(arrow.getAttribute('data-voted') != null && onePoint){
        score = parseInt(points.innerHTML) - 1;
        var payload = {
          id: commentId,
          points: -1
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            if(score == 1){
              points.innerHTML = score.toString() + " point";
            }
            else{
              points.innerHTML = score.toString() + " points";
            }
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
      }
      if(arrow.getAttribute('data-voted') == null){
        score = parseInt(points.innerHTML) + 1;
        var payload = {
          id: commentId,
          points: 1
        }
        makeRequest({
          method: 'POST',
          url: url,
          params: payload,
          headers: {
            'Content-type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          }
        }).then(function(result) {
          if(result == 'SUCCESS'){
            if(score == 1){
              points.innerHTML = score.toString() + " point";
            }
            else{
            }
          }
        })
        .catch(function (err) {
          console.error('error: ', err.statusText);
        });
      }
    }
  }

  function deleteThread(thread){
    var threadId = thread.replace("thread","");
    var url = "./controllers/deleteThreadController.php";
    var payload = {
      id: threadId
    }
    if(confirm('Are you sure you want to delete this thread?')) {
    } 
    else {
      return;
    }
    makeRequest({
      method: 'POST',
      url: url,
      params: payload,
      headers: {
        'Content-type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json'
      }
    }).then(function(result) {
      if(result == 'SUCCESS'){
        location.reload();
      }
    })
    .catch(function (err) {
      console.error('error: ', err.statusText);
    });
  }
  function deleteComment(comment){
    var commentId = comment.replace("comment","");
    var url = "./controllers/deleteCommentController.php";
    var payload = {
      id: commentId
    }
    if(confirm('Are you sure you want to delete this comment?')) {
    } 
    else {
      return;
    }
    makeRequest({
      method: 'POST',
      url: url,
      params: payload,
      headers: {
        'Content-type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json'
      }
    }).then(function(result) {
      if(result == 'SUCCESS'){
        location.reload();
      }
    })
    .catch(function (err) {
      console.error('error: ', err.statusText);
    });
  }

  function deleteForum(forum){
    var url = "./controllers/deleteForumController.php";
    var payload = {
      id: forum
    }
    if(confirm('Are you sure you want to delete this Forum?')) {
    } 
    else {
      return;
    }
    makeRequest({
      method: 'POST',
      url: url,
      params: payload,
      headers: {
        'Content-type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json'
      }
    }).then(function(result) {
      if(result == 'SUCCESS'){
        location.replace('/Forum42/');
      }
    })
    .catch(function (err) {
      console.error('error: ', err.statusText);
    });
  }