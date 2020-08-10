// create a get request to a url with paramters and use callback to work with results
function downloadUrl(url, params, callback) {
    var url = new URL(url);
    params.forEach(param => {
        url.searchParams.set(param.key, param.value);
    });

    var xhr = new XMLHttpRequest();
    xhr.open('GET', url);
    xhr.send();
    xhr.onload = function() {
        if(xhr.status != 200) {
            console.log(`Error ${this.status}: ${this.statusText}`);
        } else {
            callback(this, this.status);
            console.log('Done');
        }
    }

    xhr.onerror = function() {
        console.log('Request failed!');
    }
}

function doNothing() {}

// get parameters from url query string
function getParams(url) {
    var params = {};
    var parser = document.createElement('a');
    parser.href = url;
    var query = parser.search.substring(1);
    var parts = query.split('&');
    for(var i = 0; i < parts.length; i++) {
        var pair = parts[i].split('=');
        params[pair[0]] = decodeURIComponent(pair[1]);
    }
    return params;
}