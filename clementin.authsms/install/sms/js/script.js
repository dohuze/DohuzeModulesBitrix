function explode( delimiter, string ) {	// Split a string by string
	// 
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: kenneth
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

	var emptyArray = { 0: '' };

	if ( arguments.length != 2
		|| typeof arguments[0] == 'undefined'
		|| typeof arguments[1] == 'undefined' )
	{
		return null;
	}

	if ( delimiter === ''
		|| delimiter === false
		|| delimiter === null )
	{
		return false;
	}

	if ( typeof delimiter == 'function'
		|| typeof delimiter == 'object'
		|| typeof string == 'function'
		|| typeof string == 'object' )
	{
		return emptyArray;
	}

	if ( delimiter === true ) {
		delimiter = '1';
	}

	return string.toString().split ( delimiter.toString() );
}


	function getXmlHttp() { // потом её внедрить   за место XMLHttpRequest()
	  var xmlhttp
	  try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP')
	  } catch (e) {
		try {
		  xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')
		} catch (E) {
		  xmlhttp = false
		}
	  }
	  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		xmlhttp = new XMLHttpRequest()
	  }
	  return xmlhttp
	}
	
	function getCookie(name) {
		var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}
	
	function base64_encode( data ) {
		var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';
	 
		do {
			o1 = data.charCodeAt(i++);
			o2 = data.charCodeAt(i++);
			o3 = data.charCodeAt(i++);
	 
			bits = o1<<16 | o2<<8 | o3;
	 
			h1 = bits>>18 & 0x3f;
			h2 = bits>>12 & 0x3f;
			h3 = bits>>6 & 0x3f;
			h4 = bits & 0x3f;
	 
			enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
		} while (i < data.length);
	 
		switch( data.length % 3 ){
			case 1:
				enc = enc.slice(0, -2) + '=='; break;
			case 2:
				enc = enc.slice(0, -1) + '=';  break;
		}
	 
		return enc;
	}