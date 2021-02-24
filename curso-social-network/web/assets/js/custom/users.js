$(document).ready(function(){
   var ias = jQuery.ias({
       container: '.box-users',
       item: '.user-item',
       pagination: '.pagination',
       next: '.pagination .next_link',
       triggetPageThreshold:  4
     
   });
    
   
    ias.extension(new IASTriggerExtension({
        text: 'ver mas personas',
        offset: 2
    }));
    
   
    ias.extension(new IASSpinnerExtension({
        src: 'http://127.0.0.1:8000/assets/images/ajax-loader.gif'
    }));
     
     ias.extension(new IASNoneLeftExtension({
        text: 'no hay personas'
    }));
    
    ias.on('ready',function(event){
        followButtons();
    }); 
    ias.on('rendered',function(event){
        followButtons();
    });
   
});

//
//function followButtons(){
//    $(".btn-follow").unbind("click").click(function(){
//        $(this).addClass("hidden");
//        $(this).parent().find(".btn-unfollow").removeClass("hidden");
//        
//        $.ajax({
//           url:  URL+'follow',
//           type: 'POST',
//           data: {followed: $(this).attr("data-followed") },
//           success: function(response){
//               alert(response);
//           }
//                
//        });
//    });
//    $(".btn-unfollow").unbind("click").click(function(){
//        $(this).addClass("hidden");
//        $(this).parent().find(".btn-follow").removeClass("hidden");
//        $.ajax({
//           url:  URL+'unfollow',
//           type: 'POST',
//           data: {followed: $(this).attr("data-followed") },
//           success: function(response){
//               alert(response);
//           }
//                
//        });
//    });
//}