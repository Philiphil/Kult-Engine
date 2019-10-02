  var kacheObj = function(e)
  {
    this.key = e.getAttribute('k-caching');
    this.changed = false;
    if (localStorage[e.getAttribute('k-caching')] !== typeof undefined && localStorage[e.getAttribute('k-caching')]  !== false  && localStorage[e.getAttribute('k-caching')] != '' ) 
    {
      this.val = localStorage[e.getAttribute('k-caching')];
    }
    if (e.hasAttribute('k-caching-onload') ) 
    {
      this.val = e.value
      this.onload = true;
    }else{
      this.onload = false;
    }
    if (e.hasAttribute('k-caching-dependency')) 
    {
      this.dep = true;
      this.dependencykey = e.getAttribute('k-caching-dependency') 
      if (localStorage[ e.getAttribute('k-caching')+ e.getAttribute('k-caching-dependency') ] !== typeof undefined && localStorage[ e.getAttribute('k-caching') + e.getAttribute('k-caching-dependency') ]  !== false)
      {
       this.dependencyval = localStorage[ e.getAttribute('k-caching') + e.getAttribute('k-caching-dependency') ];
     }
   }else{
    this.dep = false;
  }
  if (e.hasAttribute('k-caching-radio')) 
  {
    this.radio = true;
    this.radiokey =  e.getAttribute('k-caching-radio') 
  }else{
    this.radio = false;
  }
  if (e.hasAttribute('k-caching-static')) 
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
  document.querySelector("[k-caching="+this.key+"]").value = localStorage[this.key];
  this.changed = true;
}

if(this.dep)
{
  if(  document.querySelector("[k-caching-dependency="+this.key+"]").value=== localStorage[this.dependencykey+this.key] && localStorage[this.key] !== '')
  {
    document.querySelector("[k-caching="+this.key+"]").value = localStorage[this.key];
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

   var elements = document.querySelectorAll("[k-caching]");
   Array.prototype.forEach.call(elements, function(el, i){
    var e = new kacheObj(el);
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
    el.addEventListener("change", function(){
      var ei = new kacheObj(el);
      if(!ei.dep)
      {
        localStorage[ei.key] = el.value
      }else{
        localStorage[ei.key] = el.value
        localStorage[ei.dependencykey+ei.key] = document.querySelector("[k-caching="+ei.dependencykey+"]").value
      }
    });

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
       document.querySelector('[k-caching='+e.key+']').dispatchEvent(new Event('change', { 'bubbles': true }))
     }else{
      _radio[e.radiokey]++;
      if(_radio[e.radiokey] === radio[e.radiokey])
      {
       document.querySelector('[k-caching='+e.key+']').dispatchEvent(new Event('change', { 'bubbles': true }))
     }
   }
  }

  });



}


function clear_kache_page()
{
 var array = [];
 var radio = {};
 var elements = document.querySelectorAll("[k-caching]");
 Array.prototype.forEach.call(elements, function(el, i){
  var e = new kacheObj(el);
  array.push(e);
  if(!e.static)
  {
    localStorage[e.key] = "";
    el.value="";
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
    document.querySelector('[k-caching='+e.key+']').dispatchEvent(new Event('change', { 'bubbles': true }))
  }else{
   _radio[e.radiokey]++;
   if(_radio[e.radiokey] === radio[e.radiokey])
   {
    document.querySelector('[k-caching='+e.key+']').dispatchEvent(new Event('change', { 'bubbles': true }))
  }
}
});



}

function is_page_kached()
{
 var i = 0
 var elements = document.querySelectorAll("[k-caching]");
 Array.prototype.forEach.call(elements, function(el, i){
  var e = new kacheObj(el);
  if (localStorage[e.key] && typeof localStorage[e.key] !== typeof undefined && localStorage[e.key] !== false && localStorage[e.key] !== '') 
  {
    if(e.dep)
    {
      if( document.querySelector("[k-caching="+e.dependencykey+"]").value == localStorage[e.dependencykey+e.key])
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


if (document.readyState !== 'loading') {
  kache_init();
} else {
  document.addEventListener('DOMContentLoaded', kache_init);
}
