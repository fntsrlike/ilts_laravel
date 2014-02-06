$(function(){

  admin = {};

  admin.run = function () {
    admin.load_list();
    admin.initial_listener();
  };

  admin.load_list = function() {
    var url = $('#loadList').attr('action');
    var data = {};

    $.post(url, data, function(data) {
        admin.make_list_table(data.msg);
        admin.reload_listener();
      },
      "json"
    );
  };

  admin.make_list_table = function(data) {
    var trs = '';
    var tr = '';
    var admin = '';

    for ( var key in data) {
        admin = data[key];
        tr = '<tr>';
        tr += '<td>' + admin['id'] + '</td>';
        tr += '<td>' + admin.username + '</td>';
        tr += '<td>' + admin.comment + '</td>';
        tr += '<td><a class="delete_btn" for="' + admin['id'] + '" href="#">刪除</a></td>';
        tr += '</tr>';
        trs += tr;
    }

    $('#listTable tbody').html(trs);
  };

  admin.initial_listener = function () {
    $("#createForm").submit(function( event ) {

        // Stop form from submitting normally
        event.preventDefault();

        // Get some values from elements on the page:
        var form    = $( this );
        var url     = form.attr('action');
        var name    = form.find( "input[name='name']" ).val();
        var comment = form.find( "input[name='comment']" ).val();

        var data = {
            "name": name,
            "comment":  comment
          };

        // Send the data using post
        $.post(url, data, function(data) {
            if (data.status == true) {
              $('#Name').val('');
              $('#Comment').val('');
              $('#createMsg').html('<span class="text-success">' + data.msg + '</span>');
              admin.load_list();
            }
            else if (data.status == false) {
              // Code..
              $('#createMsg').html('<span class="text-danger">' + data.msg + '</span>');
            }
            else {
              // Code..
              console.log("Pass but Error");
            }
          },
          "json"
        );

      });
  }

  admin.reload_listener = function () {

      $('.delete_btn').click(function(){
        if (!confirm("您確定要刪除此欄位嗎？")) {
                return false;
        }

        var me = $(this);
        var id = me.attr('for');

        var url  = 'admin/api_delete_admin';
        var data = {'id' : id};

        $.post(
          url,
          data,
          function() {
            admin.load_list();
          }
        );
      });


  };

  admin.run();

});



