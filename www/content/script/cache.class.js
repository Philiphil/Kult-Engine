  var kacheObj = function(e)
  {
  	this.key = $(e).attr('k-caching');
  	this.changed = false;
  	if (localStorage[$(e).attr('k-caching')] !== typeof undefined && localStorage[$(e).attr('k-caching')]  !== false  && localStorage[$(e).attr('k-caching')] != '' ) 
  	{
  		this.val = localStorage[$(e).attr('k-caching')];
  	}
  	if (typeof $(e).attr('k-caching-onload') !== typeof undefined && $(e).attr('k-caching-onload') !== false ) 
  	{
  		this.val = $(e).val();
  		this.onload = true;
  	}else{
  		this.onload = false;
  	}
  	if (typeof $(e).attr('k-caching-dependency') !== typeof undefined && $(e).attr('k-caching-dependency') !== false ) 
  	{
  		this.dep = true;
  		this.dependencykey = $(e).attr('k-caching-dependency');
  		if (localStorage[$(e).attr('k-caching')+$(e).attr('k-caching-dependency')] !== typeof undefined && localStorage[$(e).attr('k-caching')+$(e).attr('k-caching-dependency')]  !== false)
  		{
  			this.dependencyval = localStorage[$(e).attr('k-caching')+$(e).attr('k-caching-dependency')];
  		}
  	}else{
  		this.dep = false;
  	}
  	if (typeof $(e).attr('k-caching-radio') !== typeof undefined && $(e).attr('k-caching-radio') !== false ) 
  	{
  		this.radio = true;
  		this.radiokey = $(e).attr('k-caching-radio');
  	}else{
  		this.radio = false;
  	}
  	if (typeof $(e).attr('k-caching-static') !== typeof undefined && $(e).attr('k-caching-static') !== false ) 
  	{
  		this.static = true;
  	}else{
  		this.static = false;
  	}
  }

  kacheObj.prototype.load = function(){
  	if(this.onload)
  	{
  		localStorage[this.key] = this.val;
  		return;
  	}
  	if(this.static)
  	{
  		return;
  	}

  	if(!this.dep && localStorage[this.key] && typeof localStorage[this.key] !== typeof undefined && localStorage[this.key] !== false && localStorage[this.key] !== '')
  	{
  		$('[k-caching="'+this.key+'"]').val(localStorage[this.key]);
  		this.changed = true;
  	}

  	if(this.dep)
  	{
  		if($('[k-caching="'+this.dependencykey+'"]').val() === localStorage[this.dependencykey+this.key] && localStorage[this.key] !== '')
  		{
  			$('[k-caching="'+this.key+'"]').val(localStorage[this.key]);
  			this.changed = true;
  		}else{
  			return;
  		}
  	}
  }

  function kache_init()
  {

  	var array = [];
  	var radio = {};
  	$('[k-caching]').each(function(e){
  		var e = new kacheObj(this);
  		e.load();
  		array.push(e);
  		if(e.radio)
  		{
  			if( typeof radio[e.radiokey] !== typeof undefined && radio[e.radiokey] !== false  )
  			{
  				radio[e.radiokey]++;
  			}else{
  				radio[e.radiokey] = 0;
  			}
  		}
  	});

  	var _radio = Object.create(radio);
  	for (var i in _radio)
  	{
  		_radio[i] = 0;
  	}

  	array.forEach(function(e,i,a){
  		if(e.changed)
  		{
  			if(!e.radio)
  			{
  				$('[k-caching="'+e.key+'"]').change();
  			}else{
  				_radio[e.radiokey]++;
  				if(_radio[e.radiokey] === radio[e.radiokey])
  				{
  					$('[k-caching="'+e.key+'"]').change();
  				}
  			}
  		}

  	});

  	$('[k-caching]').change(function(x){
  		var e = new kacheObj(this);
  		if(!e.dep)
  		{
  			localStorage[e.key] = $(this).val();
  		}else{
  			localStorage[e.key] = $(this).val();
  			localStorage[e.dependencykey+e.key] = $('[k-caching="'+e.dependencykey+'"]').val();
  		}
  	});
  }


  function clear_kache_page()
  {
  	var array = [];
  	var radio = {};
  	$('[k-caching]').each(function(x){
  		var e = new kacheObj(this);
  		array.push(e);
  		if(!e.static)
  		{
  			localStorage[e.key] = "";
  			$(this).val('');
      if(e.radio)
      {
      	if( typeof radio[e.radiokey] !== typeof undefined && radio[e.radiokey] !== false  )
      	{
      		radio[e.radiokey]++;
      	}else{
      		radio[e.radiokey] = 1;
      	}
      }
  }
});
  	var _radio = Object.create(radio);
  	for (var i in _radio)
  	{
  		_radio[i] = 0;
  	}
  	array.forEach(function(e,i,a){

  		if(!e.radio)
  		{
  			$('[k-caching="'+e.key+'"]').change();
  		}else{
  			_radio[e.radiokey]++;
  			if(_radio[e.radiokey] === radio[e.radiokey])
  			{
  				$('[k-caching="'+e.key+'"]').change();
  			}
  		}
  	});



  }

  function is_page_kached()
  {
  	var i = 0
  	$('[k-caching]').each(function(x){
  		var e = new kacheObj(this);
  		if (localStorage[e.key] && typeof localStorage[e.key] !== typeof undefined && localStorage[e.key] !== false && localStorage[e.key] !== '') 
  		{
  			if(e.dep)
  			{
  				if($('[k-caching="'+e.dependencykey+'"]').val() == localStorage[e.dependencykey+e.key])
  				{
  					i++;
  				}
  			}else if(!e.onload){
  				i++;
  			}
  		}
  	});
  	return i !== 0;
  }


  $(document).ready(function(){
  	kache_init();
  });