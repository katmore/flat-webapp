

/*
 * keeps page from jumping to top with # href
 */
$('body').on('click',function(e) {
   if(e.target.tagName.toLowerCase() === 'a') {
      if ($(e.target).attr('href')=='#') {
         e.preventDefault();
      }
   }
});

