var URL = document.location.toString();
var useragent = navigator.userAgent;
useragent = useragent.toLowerCase();

if( useragent.indexOf('iphone') != -1 ){ // iphone

} else if( useragent.indexOf('ipad') != -1 || useragent.indexOf('ipod') != -1) { //ipad

}else if( useragent.indexOf('android') != -1 ) {
    if( ConsiderLimits() ) {  // android pad

    }else{ // android phone
      
    }
}else{ // PC
  
} // end if

function ConsiderLimits() {
    if( screen.width >= 1024 && screen.height >= 600 ) return 1;
    return 0;
} // end function