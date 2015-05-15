var http = require('http');
var url = require('url');
var fs = require('fs');

var port = 8000;
var ip = "178.238.232.146";
templatePath = "/template";

http.createServer(function (request, response) {
	var queryData = url.parse(request.url, true).query;
	response.writeHead(200, {'Content-Type': 'text/html'});
	if (queryData.template) {	// http://host:8000/?template=xyz
		getRequest(response, queryData);
	} else {
		showAvailableTemplates(response);
	}
}).listen(port, ip);
console.log('listening on http://' + ip + '/' + port + '/');


function showAvailableTemplates(response) {
	getFileList(function(html) {
		response.end(html);
	});

	function getFileList(callback) {
		var html = "";
		fs.readdir(__dirname + templatePath, function(err, files) {
			if (err) return;
			files.forEach(function(f) {
				if ((f.indexOf(".tpl") != -1) && (f.indexOf(".swp") == -1) && (f.indexOf(".bak") == -1))
					html += "<a href='http://" + ip + ":" + port + "/?template=" + f + "'>" + f + "</a> <br>";
			});
			callback(html);
		});
	}
}


function getRequest(response, queryData, callback) {

console.log("calling jam with args" + " : " + "template/" + queryData.template);
	var child = require('child_process').execFile('./jam', [ "template/" + queryData.template ]); 
	// use event hooks to provide a callback to execute when data are available: 
	child.stdout.on('data', function(data) {
		response.write(data);
	});
	child.stdout.on('end', function() {
		response.end();
		console.log("     completed request");
	});
}

