//============================================================
// country city state select box using ajax
//=============================================================

    // on country change 
    $("#countryId").change(function ()
    {	
         var countryId = this.value;
         state(countryId);
    });

    // on state change
    $("#stateId").change(function ()
    {	
        var stateId = this.value;
        city(stateId);
        
    });
    
   // get state list based on country id
   function state(countryId) {
     
       $.ajax({
           url: url+"/admin/get/country/states",
           method: "POST",
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           data: {
               'countryId': countryId
           },
           success: function (result) {
               var html = '';
               if (result.status == 1) {
                   html += "<option value=''>Select State</option>";
                   html += "<option value='All'>All</option>";
                    
                   $.each(result.data.states, function (i, item) {
                               
                        html += "<option value='" + item.id + "'>" + item.state + "</option>";
                   });
                   
                   $('#stateId').html(html);
               }
               
               else {
                   html += "<option value=''>Select State</option>";
                   $('#stateId').html(html);
               }
           }
       });
   }
   //country name  through get city list
   function city(stateId) {
      
       var city_id = $("#cityId").val();

       $.ajax({
           url:  url+"/admin/get/country/cities",
           method: "POST",
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           data: {
               'stateId': stateId
           },
           success: function (result)
           {

               var html = '';
               if (result.status == 1 )
               {
                   html += "<option value=''>Select City</option>";
                   html += "<option value='All'>All</option>";

                   $.each(result.data.cities, function (i, item)
                   {
                         html += "<option value='" + item.id + "'>" + item.city + "</option>";
                   });

                   $('#cityId').html(html);
               }
               else
               {
                   html += "<option value=''>Select City</option>";
                   $('#cityId').html(html);
               }
           }
       });

   }//end country function
   
 //============================================================
 //  End country city state select box using ajax
 //=============================================================