var http = require('http');
var url = require('url');
var fs = require('fs');

var port = 8000;
var ip = "178.238.232.146";
templatePath = "/template";

http.createServer(function (request, response) {
	var queryData = url.parse(request.url, true).query;
	response.writeHead(200, {'Content-Type': 'text/html'});

	if ((request.method == 'POST') && (queryData.template)) {
		var body = '';
		request.on('data', function (data) {
			body += data;
		});
		request.on('end', function () {
			console.log("Post: " + body);
			getRequest(response, "template=template/" + queryData.template,  body);
		});
	}
	else if (queryData.template) {	// http://host:8000/?template=xyz
		getRequest(response, "template=template/" + queryData.template, null);
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
		fs.readdir(__dirname + "/" + "template/", function(err, files) {
			if (err) return;
			files.forEach(function(f) {
				if ((f.indexOf(".tpl") != -1) && (f.indexOf(".swp") == -1) && (f.indexOf(".bak") == -1))
					html += "<a href='http://" + ip + ":" + port + "/?template=" + f + "'>" + f + "</a> <br>";
			});
			callback(html);
		});
	}
}

function getRequest(response, templateName, prefill, callback) {
	args = [];
	args.push(templateName);
	if (args[0].indexOf(" ") != -1)
		args[0] = "'" + args[0] + "'";
	if (prefill) {
		var nvpArr = prefill.split("&");
		for (var i = 0; i < nvpArr.length; i++) {
			var pre = '';
			var post = '';
			if (nvpArr[i].indexOf(" ") != -1) {
				pre = "'";
				post = "'";
			}
			args.push(" " + pre + nvpArr[i] + post);
		}
	}
	console.log("calling jam with args" + " : " + args);
	//var child = require('child_process').execFile('./jam', [ "template/" + queryData.template ]); 
	var child = require('child_process').execFile('./jam',  args , {}, function(){} ); 
	// use event hooks to provide a callback to execute when data are available: 
	child.stdout.on('data', function(data) {
		response.write(data);
	});
	child.stdout.on('end', function() {
		response.end();
		console.log("     completed request");
	});
}

