$(document).ready(function() {
    $(document).on('click', '.update_credit', function() {
        $('#actionicon').removeClass('fa-times');
        $('#actionicon').addClass('fa-pencil');
        $('.actionBtn').addClass('btn-success');
        $('.actionBtn').removeClass('btn-danger');
        $('.actionBtn').addClass('update_credit_info');
        $('.modal-title').text('Update Credit');
        $('.form-horizontal').show();
        $('#invoicenum').val($(this).data('invoicenum'));
        $('#creditdate').val($(this).data('creditdate'));
        $('#platenumber').val($(this).data('platenumber'));
        $('#quantity').val($(this).data('quantity'));
        $('#unitprice').val($(this).data('unitprice'));
        $('#credit_id').val($(this).data('credit_id'));
        $('#amount').val($(this).data('amount'));
        var credittype = $(this).data('credittype');
        if(credittype == 'Petrol'){
            $('#gas' + $(this).data('petrol')).prop('selected', true);
            
        }
        else if(credittype == 'Product'){
            $('#product' + $(this).data('product')).prop('selected', true);
        }
        else {

        }
        //console.log(credittype);
        $('#updateCreditModal').modal('show');
    });
    $('.modal-footer').on('click', '.update_credit_info', function() {
        $.ajax({
            type: 'post',
            url: '/billing/update_credit',
            data: {
                '_token': $('input[name=_token]').val(),
                'invoicenum': $('#invoicenum').val(),
                'creditdate': $("#creditdate").val(),
                'platenumber': $("#platenumber").val(),
                'quantity': $("#quantity").val(),
                'unitprice': $("#unitprice").val(),
                'amount': $("#amount").val(),
                'credit_id': $("#credit_id").val(),
                'product': $("#product").val()   
            },
            success: function(data) {
                location.reload();
                // new PNotify({
                //     title: 'Success',
                //     text: 'Account Successfully Updates',
                //     type: 'success',
                //     delay: 2000,
                //     styling: 'bootstrap3'
                // });
            }
            
        });
    });

    $(document).on('click', '.remove_credit', function() {
        $('#actionicon').removeClass('fa-times');
        $('#actionicon').addClass('fa-pencil');
        $('.actionBtn').addClass('btn-warning');
        $('.actionBtn').removeClass('btn-danger');
        $('.actionBtn').addClass('remove_credit_info');
        $('.modal-title').text('Remove Credit');
        $('#credit_id').val($(this).data('credit_id'));
        $('#deleteCreditModal').modal('show');
    });
    $('.modal-footer').on('click', '.remove_credit_info', function() {
        $.ajax({
            type: 'post',
            url: '/billing/remove_credit',
            data: {
                '_token': $('input[name=_token]').val(),
                'credit_id': $("#credit_id").val()
            },
            success: function(data) {
                location.reload();
            }
            
        });
    });
    $(document).on('click', '.re_assess', function() {
        $.ajax({
            type: 'post',
            url: '/billing/re_assess',
            data: {
                '_token': $('input[name=_token]').val(),
                'bill_id': $(this).data('bill_id'),
                'account_id': $(this).data('account_id'),
                'bill_date': $(this).data('bill_date')
                
            },
            success: function(data) {
                location.reload();
            }
            
        });
    });
});