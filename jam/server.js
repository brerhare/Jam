var os = require('os');
var http = require('http');
var url = require('url');
var fs = require('fs');

var port = 8000;
var ip = "178.238.232.146";
if (os.hostname() == "fry")
	ip = "127.0.0.1";
templatePath = "template";

args = [];
process.argv.forEach(function (val, index, array) {
	args.push(val);
});
for (i = 0; i < args.length; i++) {
	if (args[i] == '-port') {
		i++;
		port = args[i];
	}
	else if (args[i] == '-templatepath') {
		i++;
		templatePath = args[i];
	}
	else if (i > 1) {
		console.log('node server.js [-port] [-templatepath]');
		process.exit(0);
	}
}

http.createServer(function (request, response) {

	var queryData = url.parse(request.url, true).query;

	if ((request.method == 'POST') && (queryData.template)) {
		response.writeHead(200, {'Content-Type': 'text/html;charset=utf-8'});
		var body = '';
		request.on('data', function (data) {
			body += data;
		});
		request.on('end', function () {
			console.log("Post: " + body);
//var req = decodeURIComponent(body)
//console.log("POST Request: ---> "+ req+ " <---");
			body = body.replace(/\+/g , " ");
			getRequest(response, "template=" + templatePath + "/" + queryData.template,  decodeURIComponent(body));
			//response.end();
		});
	} else if (queryData.template) {	// http://host:8000/?template=xyz
		response.writeHead(200, {'Content-Type': 'text/html;charset=utf-8'});
		getRequest(response, "template=" + templatePath + "/" + queryData.template, null);
	} else if (request.url.indexOf('.js') != -1) {
		fileName = __dirname + "/" + request.url;
		fs.readFile(fileName, function (err, data) {
			response.writeHead(200, {'Content-Type': 'text/js;charset=utf-8'});
			if (err)
				console.log("no file " + fileName);
			else
				response.write(data);
			response.end();
		});
	} else if (request.url.indexOf('.css') != -1) {
		fileName = __dirname + "/" + request.url;
		fs.readFile(fileName, function (err, data) {
			response.writeHead(200, {'Content-Type': 'text/css;charset=utf-8'});
			if (err)
				console.log("no file " + fileName);
			else
				response.write(data);
			response.end();
		});
	} else if (request.url.indexOf('.html') != -1) {
		fileName = __dirname + "/" + request.url;
		fs.readFile(fileName, function (err, data) {
			response.writeHead(200, {'Content-Type': 'text/html;charset=utf-8'});
			if (err)
				console.log("no file " + fileName);
			else
				response.write(data);
			response.end();
		});
	} else if (request.url != "/") {
		fileName = __dirname + "/" + request.url;
		fs.readFile(fileName, function (err, data) {
			response.writeHead(200, {'Content-Type': 'text/html;charset=utf-8'});
			if (err)
				console.log("no file " + fileName);
			else
				response.write(data);
			response.end();
		});
	} else {
//console.log("["+request.url+"]");
		showAvailableTemplates(response);
	}
//}).listen(port, ip);
}).listen(port, ip);
console.log('templatepath is ' + templatePath);
console.log('listening on http://' + ip + ':' + port);

function showAvailableTemplates(response) {
	response.writeHead(200, {'Content-Type': 'text/html;charset=utf-8'});
	getFileList(function(html) {
		response.end(html);
	});
	function getFileList(callback) {
		var html = "";
		fs.readdir(__dirname + "/" + templatePath + "/", function(err, files) {
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
//console.log("TEMPLATENAME--->[" + templateName + "]");
console.log("PREFILL--->[" + prefill + "]");
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
	var spawn = require('child_process').spawn;
	var cspr = spawn("./jam", args);
	cspr.stdout.setEncoding('utf8');

	cspr.stdout.on('data', function (data) {
		var buf = new Buffer(data);
		response.write(data);
//		console.log(buf.toString('utf8'));		// echo the data
	});

	cspr.stderr.on('data', function (data) {
		data += '';
		console.log(data.replace("\n", "\nstderr: "));	// echo the error
		response.write(data);
	});

	cspr.on('exit', function (code) {
		response.end();
    	console.log('finished with result code ' + code);
	});

}

