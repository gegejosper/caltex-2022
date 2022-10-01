$(document).ready(function() {
    $("#accountsale").hide();
    $("#paymentoption input").change(function() {
        var val = $("input[name=payment]:checked").val();  
        if(val != undefined) {
            if (val == "CREDIT") {
            $("#accountsale").show();
            } else {
            $("#accountsale").hide();
            }
            //console.log('dsads');
        }
        //console.log('dsads');
    });
    $(".branchgascredit").change(function () {
        var gas = $('select[name=branchgas]').val();
         var array = gas.split(',');
         var gasname = array[0];
         var gasid = array[1]; 
         var gasprice = array[2]; 
       $('#creditunitprice').val(gasprice);
    });
    $(".salebranchproduct").change(function () {
        var product = $('select[name=salebranchproduct]').val();
         var array = product.split(',');
         var productname = array[0];
         var productid = array[1]; 
         var productprice = array[2]; 
       $('#saleprice').val(productprice);
    });
    $('#creditliters').keyup(function() {  
        updateCreditAmount();
    });
    $('#creditdate').keyup(function() {  
        $('#disdate').val($(this).val());
        $('#salesdate').val($(this).val());
        $('#othersdate').val($(this).val());
        $('#carddate').val($(this).val());  
    });

    $('#disdate').keyup(function() {  
        $('#creditdate').val($(this).val());
        $('#salesdate').val($(this).val());
        $('#othersdate').val($(this).val());
        $('#carddate').val($(this).val());  
    });
    $('#salesdate').keyup(function() {  
        $('#creditdate').val($(this).val());
        $('#disdate').val($(this).val());
        $('#othersdate').val($(this).val());
        $('#carddate').val($(this).val());  
    });
    $('#othersdate').keyup(function() {  
        $('#creditdate').val($(this).val());
        $('#disdate').val($(this).val());
        $('#salesdate').val($(this).val());
        $('#carddate').val($(this).val());  
    });
    $('#carddate').keyup(function() {  
        $('#creditdate').val($(this).val());
        $('#disdate').val($(this).val());
        $('#salesdate').val($(this).val());
        $('#othersdate').val($(this).val());  
    });
      var updateCreditAmount = function () {
      var creditliters = parseFloat($('#creditliters').val()).toFixed(2);
      var creditunitprice = parseFloat($('#creditunitprice').val()).toFixed(2);
      var creditamount = creditliters *  creditunitprice;
        $('#creditamount').val(parseFloat(creditamount).toFixed(3));
    };

    $('#salequantity').keyup(function() {  
        updateSaleAmount();
    });
      var updateSaleAmount = function () {
      var salequantity = parseFloat($('#salequantity').val()).toFixed(2);
      var saleprice = parseFloat($('#saleprice').val()).toFixed(2);
      var saleamount = salequantity *  saleprice;
        $('#saleamount').val(parseFloat(saleamount).toFixed(3));
    };
    $("#creditadd").click(function() {
        $('#creditadd').attr('disabled', true);
          $.ajax({
              type: 'post',
              url: '/incharge/dashboard-creditadd',
              data: {
                  '_token': $('input[name=_token]').val(),
                  'invoice': $('input[name=creditinvoicenum]').val(),
                  'account': $('select[name=accountcredit]').val(),
                  'gasname': $('#branchgas').val(),
                  'creditliters': $('input[name=creditliters]').val(),
                  'branchid': $('input[name=branchid]').val(),
                  'creditplatenum': $('input[name=creditplatenum]').val(),
                  'creditamount': $('input[name=creditamount]').val(),
                  'unitprice': $('input[name=creditunitprice]').val(),
                  'creditdate': $('input[name=creditdate]').val()
              },
              success: function(data) {
                  if ((data.errors)){
                    $('.error').removeClass('hidden');
                      $('.error').text(data.errors.name);
                      new PNotify({
                        title: 'Error',
                        text: 'Please complete the details.',
                        type: 'warning',
                        delay: 2000,
                        styling: 'bootstrap3'
                    }); 
                    
                  }
                  else {
                      $('.error').addClass('hidden');
                      $('#creditadd').attr('disabled', false);  
                      var gasnameValue = data.gasname;
                      var gasname = gasnameValue.split(',');
                      var accountValue = data.account;
                      var account = accountValue.split(',');
                      
                      $('#credittable').append("<tr class='credititem" + data.id + "'><td>"+ data.creditdate  +"</td><td>"+ data.invoice  +"</td><td>" + account[0] + ", " + account[1] +" </td><td>" + gasname[0]+ " </td><td>" + data.creditplatenum + "</td><td>" + data.liters + "</td><td>" + data.unitprice + "</td><td>" + data.amount + "</td><td class='td-actions'><a class='delete-credit btn btn-danger btn-xs' data-id='" + data.id + "' data-amount='" + data.amount + "'><i class='fa fa-times'></i></a></td></tr>");
                        new PNotify({
                        title: 'Success',
                        text: 'Credit successfully added',
                        type: 'success',
                        delay: 2000,
                        styling: 'bootstrap3'
                        }); 
                    }
              },
          });
            
            var total_credit = parseFloat($('#total_credit_amount').text().replace(',' ,''));
            var credit_amount = parseFloat($('input[name=creditamount]').val());
            var total_credit_amount_value = parseFloat(total_credit + credit_amount);
            $('#total_credit_amount').text(total_credit_amount_value);
            
            $('#creditinvoicenum').val('');
            $('#creditplatenum').val('');
            $('#creditliters').val('0');
            $('#creditamount').val('0');
            $('#creditunitprice').val('0');
            $('#branchgas option:selected').removeAttr('selected');
            $('#nocreditrecord').remove();
    });
    $(document).on('click', '.delete-credit', function() {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').removeClass('discountdelete');
        $('.actionBtn').removeClass('saledelete');
        $('.actionBtn').removeClass('otherdelete');
        $('.actionBtn').removeClass('carddelete');
        $('.actionBtn').addClass('btn-danger');
        $('.actionBtn').addClass('creditdelete');
        $('.modal-title').text('Delete');
        $('.did').text($(this).data('id'));
        $('#deleteamount').val($(this).data('amount'));
        $('.deleteContent').show();
        $('.dname').html($(this).data('name'));
        $('#myModal').modal('show');
    }); 
    $('.modal-footer').on('click', '.creditdelete', function() {
          $.ajax({
              type: 'post',
              url: '/incharge/dashboard-creditdelete',
              data: {
                  '_token': $('input[name=_token]').val(),
                  'id': $('.did').text(),
                  'deleteamount': $('input[name=deleteamount]').val()
              },
              success: function(data) {
                new PNotify({
                    title: 'Success',
                    text: 'Credit record deleted',
                    type: 'danger',
                    delay: 2000,
                    styling: 'bootstrap3'
                });  
                $('.credititem' + $('.did').text()).remove();

                var total_credit = parseFloat($('#total_credit_amount').text().replace(',' ,''));
                var delete_credit_amount = parseFloat($('input[name=deleteamount]').val());
                var total_credit_amount = parseFloat(total_credit - delete_credit_amount);
                $('#total_credit_amount').text(total_credit_amount);

                }
            });
            
    });
    $("#discountadd").click(function() {
          //console.log('cdsada');
        $('#discountadd').attr('disabled', true);
        $.ajax({
            type: 'post',
            url: '/incharge/dashboard-discountadd',
            data: {
                '_token': $('input[name=_token]').val(),
                'account': $('select[name=accountdis]').val(),
                'gasname': $('#branchgasdis').val(),
                'branchid': $('input[name=branchid]').val(),
                'displatenum': $('input[name=displatenum]').val(),
                'disamount': $('input[name=disamount]').val(),
                'disdate': $('input[name=disdate]').val()
            },
            success: function(data) {
                if ((data.errors)){
                  $('.error').removeClass('hidden');
                    $('.error').text(data.errors.name);
                    new PNotify({
                      title: 'Error',
                      text: 'Please complete the details.',
                      type: 'warning',
                      delay: 2000,
                      styling: 'bootstrap3'
                  }); 
                }
                else {
                    $('#discountadd').attr('disabled', false);
                    $('.error').addClass('hidden');
                    var gasnameValue = data.gasname;
                    var gasname = gasnameValue.split(',');
                    var accountValue = data.account;
                    var account = accountValue.split(',');
                    
                    $('#discounttable').append("<tr class='discountitem" + data.id + "'><td>" +data.discountdate + " </td><td>" + account[0] + ", " + account[1] +" </td><td>" + gasname[0]+ " </td><td>" + data.platenum + "</td><td>" + data.amount + "</td><td class='td-actions'><a class='delete-discount btn btn-danger btn-xs' data-id='" + data.id + "' data-amount='" + data.amount + "'><i class='fa fa-times'></i></a></td></tr>");
                    new PNotify({
                      title: 'Success',
                      text: 'Discount successfully added!',
                      type: 'success',
                      delay: 2000,
                      styling: 'bootstrap3'
                  }); 
                  }
                
            },

        });
        
        $('#branchgasdis option:selected').removeAttr('selected');
        $('#nodiscountrecord').remove();
        var total_sales = parseFloat($('#total_discount_amount').text().replace(',' ,''));
        var discount_amount = parseFloat($('input[name=disamount]').val());
        var total_discount_amount_value = parseFloat(total_sales + discount_amount);
        $('#total_discount_amount').text(total_discount_amount_value);
        // console.log($totalDiscount);
        // console.log($('input[name=disamount]').val());
        $('#displatenum').val('');
        $('#disamount').val('0');
        $('#creditamount').val('0');
    });
    $(document).on('click', '.delete-discount', function() {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').removeClass('creditdelete');
        $('.actionBtn').removeClass('saledelete');
        $('.actionBtn').removeClass('otherdelete');
        $('.actionBtn').removeClass('carddelete');
        $('.actionBtn').addClass('btn-danger');
        $('.actionBtn').addClass('discountdelete');
        $('.modal-title').text('Delete');
        $('.did').text($(this).data('id'));
        $('#deleteamount').val($(this).data('amount'));
        $('.deleteContent').show();
        $('.dname').html($(this).data('name'));
        $('#myModal').modal('show');
     }); 
    $('.modal-footer').on('click', '.discountdelete', function() {
          $.ajax({
              type: 'post',
              url: '/incharge/dashboard-discountdelete',
              data: {
                  '_token': $('input[name=_token]').val(),
                  'id': $('.did').text(),
                  'deleteamount': $('input[name=deleteamount]').val()
              },
              success: function(data) {
                new PNotify({
                    title: 'Success',
                    text: 'Discount record deleted',
                    type: 'danger',
                    delay: 2000,
                    styling: 'bootstrap3'
                });  
                $('.discountitem' + $('.did').text()).remove();

                var total_discount = parseFloat($('#total_discount_amount').text().replace(',' ,''));
                var delete_discount_amount = parseFloat($('input[name=deleteamount]').val());
                var total_discount_amount = parseFloat(total_discount - delete_discount_amount);
                $('#total_discount_amount').text(total_discount_amount);
                }
            });
           
      });
////////// Sales JS Code

    $("#salesadd").click(function() {
        $('#salesadd').attr('disabled', true);
        $.ajax({
            type: 'post',
            url: '/incharge/dashboard-salesadd',
            data: {
                '_token': $('input[name=_token]').val(),
                'invoice': $('input[name=saleinvoicenum]').val(),
                'account': $('select[name=accountsale]').val(),
                'salebranchproduct': $('#salebranchproduct').val(),
                'saleprice': $('input[name=saleprice]').val(),
                'branchid': $('input[name=branchid]').val(),
                'paymenttype' : $("input[name=payment]:checked").val(),
                'salequantity': $('input[name=salequantity]').val(),
                'saleamount': $('input[name=saleamount]').val(),
                'salesdate': $('input[name=salesdate]').val()
            },
            success: function(data) {
                if ((data.errors)){
                $('.error').removeClass('hidden');
                    $('.error').text(data.errors.name);
                    new PNotify({
                    title: 'Error',
                    text: 'Please complete the details.',
                    type: 'warning',
                    delay: 2000,
                    styling: 'bootstrap3'
                }); 
                }
                else {
                    $('.error').addClass('hidden');
                    $('#salesadd').attr('disabled', false);
                    var productValue = data.product;
                    var product = productValue.split(',');
                    var accountValue = data.account;
                    var name = "";
                    var account = accountValue.split(',');
                    if(data.paymenttype === 'CASH')
                    {
                        name = "";
                    }
                    else {
                        name = account[0] + ", " + account[1] 
                    }
                    $('#saletable').append("<tr class='saleitem" + data.id + "'><td>"+ data.saledate  +"</td><td>"+ data.invoice  +"</td><td>"+ data.paymenttype +"</td><td>" + name + " </td><td>" + product[0]+ " </td><td>" + data.quantity + "</td><td>" + data.price + "</td><td>" + data.amount + "</td><td class='td-actions'><a class='delete-sale btn btn-danger btn-xs' data-id='" + data.id + "' data-amount='" + data.amount + "'><i class='fa fa-times'></i></a></td></tr>");
                    new PNotify({
                    title: 'Success',
                    text: 'Sales record successfully added!',
                    type: 'success',
                    delay: 2000,
                    styling: 'bootstrap3'
                    }); 
                }
                
            },

        });
        var total_sales = parseFloat($('#total_sale_amount').text().replace(',' ,''));
        var sale_amount = parseFloat($('input[name=saleamount]').val());
        var total_sales_amount_value = parseFloat(total_sales + sale_amount);
        $('#total_sale_amount').text(total_sales_amount_value);

        $('#saleprice').val('0');
        $('#salequantity').val('0');
        $('#saleamount').val('0');
        $('#accountsale option:selected').removeAttr('selected');
        $('#salebranchproduct option:selected').removeAttr('selected');
        $('#nosalesrecord').remove();
    });

    $(document).on('click', '.delete-sale', function() {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').removeClass('discountdelete');
        $('.actionBtn').removeClass('creditdelete');
        $('.actionBtn').removeClass('otherdelete');
        $('.actionBtn').removeClass('carddelete');
        $('.actionBtn').addClass('btn-danger');
        $('.actionBtn').addClass('saledelete');
        $('.modal-title').text('Delete');
        $('.did').text($(this).data('id'));
        $('#deleteamount').val($(this).data('amount'));
        $('.deleteContent').show();
        $('.dname').html($(this).data('name'));
        $('#myModal').modal('show');
    }); 
    $('.modal-footer').on('click', '.saledelete', function() {
        $.ajax({
            type: 'post',
            url: '/incharge/dashboard-salesdelete',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $('.did').text(),
                'deleteamount': $('input[name=deleteamount]').val()
            },
            success: function(data) {
            new PNotify({
                title: 'Success',
                text: 'Sales record deleted',
                type: 'danger',
                delay: 2000,
                styling: 'bootstrap3'
            });  
                $('.saleitem' + $('.did').text()).remove();

                var total_sales = parseFloat($('#total_sale_amount').text().replace(',' ,''));
                var delete_sale_amount = parseFloat($('input[name=deleteamount]').val());
                var total_sales_amount = parseFloat(total_sales - delete_sale_amount);
                $('#total_sale_amount').text(total_sales_amount);
            }
        });
       
    });
//// Others JS Code


    $("#othersadd").click(function() {
        $('#othersadd').attr('disabled', true);
        $.ajax({
            type: 'post',
            url: '/incharge/dashboard-othersadd',
            data: {
                '_token': $('input[name=_token]').val(),
                'branchid': $('input[name=branchid]').val(),
                'desc' : $("input[name=description]").val(),
                'amount': $('input[name=descamount]').val(),
                'othersdate': $('input[name=othersdate]').val()
            },
            success: function(data) {
                if ((data.errors)){
                $('.error').removeClass('hidden');
                    $('.error').text(data.errors.name);
                    new PNotify({
                    title: 'Error',
                    text: 'Please complete the details.',
                    type: 'warning',
                    delay: 2000,
                    styling: 'bootstrap3'
                }); 
                    $('#othersadd').attr('disabled', false);
                }
                else {
                    $('.error').addClass('hidden');
                    $('#othersadd').attr('disabled', false);
                    $('#otherstable').append("<tr class='otheritem" + data.id + "'><td>"+ data.othersdate  +"</td><td>"+ data.desc  +"</td><td>"+ data.amount +"</td><td class='td-actions'><a class='delete-other btn btn-danger btn-xs' data-id='" + data.id + "' data-amount='" + data.amount + "'><i class='fa fa-times'></i></a></td></tr>");
                    new PNotify({
                    title: 'Success',
                    text: 'Others successfully Added',
                    type: 'success',
                    delay: 2000,
                    styling: 'bootstrap3'
                    }); 
                    $('#othersadd').attr('disabled', false);
                }
                
            },

        });
        var total_others = parseFloat($('#total_others_amount').text().replace(',' ,''));
        var others_amount = parseFloat($('input[name=descamount]').val());
        var total_others_amount_value = parseFloat(total_others + others_amount);
        $('#total_others_amount').text(total_others_amount_value);

        $('#description').val('');
        $('#descamount').val('0');
        // $('#salequantity').val('0');
        // $('#saleamount').val('0');
        // $('#accountsale option:selected').removeAttr('selected');
        // $('#salebranchproduct option:selected').removeAttr('selected');
        $('#nootherrecord').remove();
        
    });

    $(document).on('click', '.delete-other', function() {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').removeClass('discountdelete');
        $('.actionBtn').removeClass('creditdelete');
        $('.actionBtn').removeClass('saledelete');
        $('.actionBtn').removeClass('carddelete');
        $('.actionBtn').addClass('btn-danger');
        $('.actionBtn').addClass('otherdelete');
        $('.modal-title').text('Delete');
        $('.did').text($(this).data('id'));
        $('#deleteamount').val($(this).data('amount'));
        $('.deleteContent').show();
        $('.dname').html($(this).data('name'));
        $('#myModal').modal('show');
    }); 
    
    $('.modal-footer').on('click', '.otherdelete', function() {
        $.ajax({
            type: 'post',
            url: '/incharge/dashboard-othersdelete',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $('.did').text(),
                'deleteamount': $('input[name=deleteamount]').val()
            },
            success: function(data) {
                new PNotify({
                    title: 'Success',
                    text: 'Others record deleted',
                    type: 'danger',
                    delay: 2000,
                    styling: 'bootstrap3'
                });  
                $('.otheritem' + $('.did').text()).remove();
                var total_others = parseFloat($('#total_others_amount').text().replace(',' ,''));
                var delete_others_amount = parseFloat($('input[name=deleteamount]').val());
                var total_others_amount = parseFloat(total_others - delete_others_amount);
                $('#total_others_amount').text(total_others_amount);
            }
            
        });
        
    });
//  Star Card Code
    $("#starcard").click(function() {
        $('#starcard').attr('disabled', true);
        $.ajax({
            type: 'post',
            url: '/incharge/dashboard-starcard',
            data: {
                '_token': $('input[name=_token]').val(),
                'branchid': $('input[name=branchid]').val(),
                'amount': $('input[name=cardamount]').val(),
                'note': $('input[name=note]').val(),
                'star_card_date': $('input[name=carddate]').val()
            },
            success: function(data) {
                if ((data.errors)){
                $('.error').removeClass('hidden');
                    $('.error').text(data.errors.name);
                    new PNotify({
                    title: 'Error',
                    text: 'Please complete the details.',
                    type: 'warning',
                    delay: 2000,
                    styling: 'bootstrap3'
                }); 
                }
                else {
                    $('.error').addClass('hidden');
                    $('#starcard').attr('disabled', false);
                    $('#cardtable').append("<tr class='carditem" + data.id + "'><td>"+ data.amount  +"</td><td>"+ data.note +"</td><td class='td-actions'><a class='delete-card btn btn-danger btn-xs' data-id='" + data.id + "' data-amount='" + data.amount + "'><i class='fa fa-times'></i></a></td></tr>");
                    new PNotify({
                    title: 'Success',
                    text: 'Star card successfully added',
                    type: 'success',
                    delay: 2000,
                    styling: 'bootstrap3'
                    }); 
                }
                
            },

        });
        var total_starcard = parseFloat($('#total_starcard_amount').text().replace(',' ,''));
        var starcard_amount = parseFloat($('input[name=cardamount]').val());
        var total_starcard_amount_value = parseFloat(total_starcard + starcard_amount);
        $('#total_starcard_amount').text(total_starcard_amount_value);

        $('#cardamount').val('');
        $('#note').val('');
        $('#nocardrecord').remove();
    });

    $(document).on('click', '.delete-card', function() {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').removeClass('discountdelete');
        $('.actionBtn').removeClass('creditdelete');
        $('.actionBtn').removeClass('saledelete');
        $('.actionBtn').removeClass('otherdelete');
        $('.actionBtn').addClass('carddelete');
        $('.actionBtn').addClass('btn-danger');
        $('.modal-title').text('Delete');
        $('.did').text($(this).data('id'));
        $('#deleteamount').val($(this).data('amount'));
        $('.deleteContent').show();
        $('.dname').html($(this).data('name'));
        $('#myModal').modal('show');
    }); 

    $('.modal-footer').on('click', '.carddelete', function() {
        $.ajax({
            type: 'post',
            url: '/incharge/dashboard-starcarddelete',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $('.did').text(),
                'deleteamount': $('input[name=deleteamount]').val()
            },
            success: function(data) {
                new PNotify({
                    title: 'Success',
                    text: 'Starcard record deleted',
                    type: 'danger',
                    delay: 2000,
                    styling: 'bootstrap3'
                });  
                $('.carditem' + $('.did').text()).remove();
                var total_starcard = parseFloat($('#total_starcard_amount').text().replace(',' ,''));
                var delete_amount = parseFloat($('input[name=deleteamount]').val());
                var total_starcard_amount_value = parseFloat(total_starcard - delete_amount);
                $('#total_starcard_amount').text(total_starcard_amount_value);
            }
        });
        
    });

// End Star Card Code
    $(document).on('click', '.clear-cache', function() {
        $('#clearModal').modal('show');
    }); 
    $(document).on('submit', '#save_daily_sales', function() {
        $('#save_daily_sales').attr('disabled', true);
    }); 
    $('.modal-footer').on('click', '.clear', function() {
        window.location = '/incharge/clear_cache';
    });
});
  