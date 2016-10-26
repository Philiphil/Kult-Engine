var UrlAjax = UrlAjax || {};
UrlAjax.demo = '/api/demo.ajax.php';

var ReqAjax = function(req,args=0)
{
	this.req = req;
	this.args = args;
}

ReqAjax.prototype.send = function(url, callback=1){
	$.get(url,
		{
			req : this.req,
			args : this.args
		}, 
		function(result=1)
		{
			if(callback === 1)
			{
				return 1;
			}else{
				callback(result);
			}
		}
	);
}

ReqAjax.prototype.sendPost = function(url, callback=1){
	$.post(url,
		{
			req : this.req,
			args : this.args
		}, 
		function(result=1)
		{
			if(callback === 1)
			{
				return 1;
			}else{
				callback(result);
			}
		}
	);
}