$(document).ready(function(){
   var ias = jQuery.ias({
       container: '#timeline .box-content',
       item: '.publication-item',
       pagination: '.pagination',
       next: '#timeline .pagination .next_link',
       triggetPageThreshold:  3
     
   });
    
   
    ias.extension(new IASTriggerExtension({
        text: 'ver mas publicaciones',
        offset: 4
    }));
    
   
    ias.extension(new IASSpinnerExtension({
        src: 'http://127.0.0.1:8000/assets/images/ajax-loader.gif'
    }));
     
     ias.extension(new IASNoneLeftExtension({
        text: 'no hay publicaciones'
    }));
    
    ias.on('ready',function(event){
        buttons();
    }); 
    ias.on('rendered',function(event){
        buttons();
    });
   
});


function buttons(){
    $('[data-toggle="tooltip"]').tooltip();
      $('[data-toggle-delete="tooltip"]').tooltip();
   
    
    
    $(".btn-img").unbind("click").click(function(){
        $(this).parent().find('.pub-image').fadeToggle();
        
    });
    
    $(".btn-delete-pub").unbind('click').click(function(){
        $(this).parent().addClass('hidden');
        
        $.ajax({
            url: URL+'publication/remove/'+$(this).attr("dataa-id"),
            type: 'GET',
            success: function(response){
                alert(response);
            }
        });
        
    });
    
    $(".btn-like").unbind('click').click(function(){
        $(this).addClass("hidden");
        $(this).parent().find('.btn-unlike').removeClass("hidden");  
        
        $.ajax({
           url: URL+'like/'+$(this).attr("data-id"),
           type: 'GET',
           success: function(response){
               alert(response);
           }
        });
        
    });
    
    $(".btn-unlike").unbind('click').click(function(){
        $(this).addClass("hidden");
        $(this).parent().find('.btn-like').removeClass("hidden");  
        
        $.ajax({
           url: URL+'unlike/'+$(this).attr("data-id"),
           type: 'GET',
           success: function(response){
               alert(response);
           }
        });
        
    });
    
}