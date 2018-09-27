var UrlAjax = UrlAjax || {};
UrlAjax.demo = '/api/demo.ajax.php';


var ReqAjax = function(req,args=0,method="POST",rep="JSON")
{
    this.req = req;
    this.args = args;
    this.method = method
    this.token = Math.floor(Math.random() * 1000) + 1  
    this.rep = rep
}

ReqAjax.prototype.send = function(url, callback=1, fallback=1){
    var data = new FormData()
    data.append("req",this.req)
    data.append("args",Array.isArray(this.args) ? JSON.stringify(this.args): JSON.stringify([this.args]) )
    data.append("token",this.token)
    var x = this.rep
    fetch(url,{
        method : this.method,
        mode : "cors",
        credentials : "same-origin",
        redirect : "follow",
        body: data
        
    }).then( function(result){ 
        switch(x)
        {
            case "JSON":
                return result.json()
                break
            case "TXT":
            case "TEXT":
                return result.text()
                break
            case "BLOB":
                return result.blob()
        }
    }).then( function(r){
        if(callback !== 1)
            {
                callback(r);
            }
    }).catch(function(error){
    if(fallback === 1)
        {
            console.debug(error)
        }else{
            fallback(error);
        }
    })
}



var SimpleReqAjax = function(req,args=0)
{
    this.req = req;
    this.args = args;
}

SimpleReqAjax.prototype.send = function(url, callback=1){
    $.get(url,
        {
            req : this.req,
            args : this.args
        }, 
        function(result=1)
        {
            if(callback === 1)
            {
                return result;
            }else{
                callback(result);
            }
        }
    );
}

SimpleReqAjax.prototype.sendPost = function(url, callback=1){
    $.post(url,
        {
            req : this.req,
            args : this.args
        }, 
        function(result=1)
        {
            if(callback === 1)
            {
                return result;
            }else{
                callback(result);
            }
        }
    );
}
SimpleReqAjax.prototype.ajax = function(url,type="GET",callback=1,fallback=-1)
{
    $.ajax({
        url : url,
        method : type,
        data : {req : this.req, args : this.args}
    }).done(function(c=1){
        if(callback === 1)
        {
            return c;
        }else{
            callback(c);
        }
    }).fail(function(){
        if(fallback === -1)
        {
            return fallback;
        }else{
            fallback();
        }
    });
}

SimpleReqAjax.prototype.promise = function(url, type="GET",callback=1,fallback=-1)
{
    x = this;
    return new Promise(function(resolve){x.ajax(url,type,callback,fallback);});
}





function Queue(){
	this.queue = [];
	this.running = 0;
}

Queue.prototype.add = function(e)
{
	this.queue.push(e);
}

Queue.prototype.uniq = function()
{
	var uniq = new Set();
	this.queue.forEach(e => uniq.add(JSON.stringify(e)));
	this.queue = Array.from(uniq).map(e => JSON.parse(e));
}

Queue.prototype.contains = function(obj)
{
	var a = new Set();
	this.queue.forEach(e => a.add(JSON.stringify(e)));
	var sa =  Array.from(a);
	var sobj = JSON.stringify(obj);
	var retour = 0;
	sa.forEach(function(e,i,a){
		if(e === sobj) retour = 1;
	});
	return retour;
}

Queue.prototype.run = function(x)
{
	if(!!this.running) return -1;
	if(this.queue.length == 0) return 0;

	this.running = 1;
	x(this.queue.shift());
	this.running = 0;
}

function QueueAjax(){}
QueueAjax.prototype = new Queue();
QueueAjax.prototype.add = Queue.prototype.add;
QueueAjax.prototype.uniq = Queue.prototype.uniq;
QueueAjax.prototype.contains = Queue.prototype.contains;

QueueAjax.prototype.run  = async function(x)
{
	if(!!this.running) return -1;
	if(this.queue.length == 0) return 0;

	this.running = 1;
	var y = await x(this.queue.shift());
	this.running = 0;
	return y;
}
