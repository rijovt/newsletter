@extends('layouts.app')

@section('content')    
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">                    
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Subscribers List</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="border" method="post" id="formData" autocomplete="off">
                            @csrf

                            <div class="p-lg-3">
                                <div class="row">
                                    <div class="col-4 name">               
                                        <label class="form-control-label" for="name">Name</label>
                                        <span class="form_error text-danger float-right">Name Required.</span>
                                        <input type="text" name="name" id="name" class="form-control">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-control-label" for="email">Email</label>
                                        <span id="email-err" class="form_error text-danger float-right">Email Required.</span>
                                        <span class="form_error text-danger float-right" id="invalid_email">Valid Email Required.</span>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="me@example.com">
                                    </div>
                                    <div class="col-4">
                                        <input type="button" id="btn-add" value="Add To Subscribe" class="form-control btn btn-sm btn-success" >
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('compose') }}" onsubmit="return valid();">
                        @csrf
                            <table class="List table table-bordered">
                                <thead class="thead-light text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Subscribed on</th>
                                    <th scope="col">Action</th>
                                </thead>                        
                                <tbody id="links-list">
                                    @php $i = count($subscribers) @endphp
                                    @foreach ($subscribers as $subscriber)
                                        <tr class="text-center" id="row_{{ $subscriber->id }}">
                                            <td>
                                                <input type="checkbox" name="checks[]" class="checkEach" value="{{ $subscriber->id }}">
                                                <span class="nos p-1">{{ $i-- }}</span>
                                            </td>
                                            <td>{{ $subscriber->name }}</td>
                                            <td>{{ $subscriber->email }}</td>
                                            <td>{{ date('d/m/Y', strtotime($subscriber->created_at ?? $subscriber->created_at)) }}</td>
                                            <td>
                                                <a href="javascript:void(0)" onclick="deleteItem({{ $subscriber->id }})" ><span aria-hidden="true">&times;</span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>                        
                            </table>

                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-9"><input type="checkbox" id="checkAll"> Check All</div>
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            {{ __('Compose Mail') }}
                                        </button>                                  
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('.form_error').hide();
    $("#name,#email").keyup(function(){
        $('.form_error').hide();
    });

    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $('#btn-add').click(function(e){
        e.preventDefault();
        var name = $('#name').val();
        var email = $('#email').val();
        if(name== ''){
          $('#name').prev().show();
          $('#name').focus()
          return false;
        }
        if(email== ''){
           $('#email-err').show();
           $('#email').focus()
           return false;
        }
        if(IsEmail(email)==false){
            $('#invalid_email').show();
            $('#email').focus()
            return false;
        }
        else{
            $.ajax({
                type: "POST",
                url: `/additem`,
                data: $("#formData").serialize(),
                dataType: 'json',
                success: function (response) {
                    var link = '<tr class="text-center" id="row_' + response.id + '"><td><input type="checkbox" name="checks[]" class="checkEach" value="' + response.id + '"><span class="nos p-1"></span></td><td>' + response.name + '</td><td>' + response.email + '</td><td>' + (new Date(response.created_at)).toLocaleDateString() + '</td>';
                    link += '<td><a href="javascript:void(0)" onclick="deleteItem(' + response.id + ')" ><span aria-hidden="true">&times;</span></a></td></tr>';
                    
                    $('#links-list').prepend(link);                                 
                    $('#formData').trigger("reset");                    
                    
                    var nos = parseInt($('.nos').length);
                    $('.nos').each(function(i, obj) {
                        $(this).text( nos-- );
                    });
                },
                error: function (data) {
                    if(data.responseJSON.errors.name)
                        alert(data.responseJSON.errors.name);
                    else if(data.responseJSON.errors.email)
                        alert(data.responseJSON.errors.email);
                    else
                        alert('Something Went Wrong, Please check data entered !!');
                }
            });
        }
    });
});

function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!regex.test(email)) {
       return false;
    }else{
       return true;
    }
}

// Clicking delete
function deleteItem(id){
    if(confirm('Are You Sure To Remove Subscription  ?')){
        let _url = `/destroyitem/${id}` 
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: _url,
            type: 'DELETE',
            data: {
              _token: _token
            },
            success: function(response) {
                $("#row_"+id).remove();

                var nos = parseInt($('.nos').length);
                $('.nos').each(function(i, obj) {
                    $(this).text( nos-- );
                });
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
}

function valid() {
    if(!$(".checkEach:checked").length) {
        alert('Select atleast one subscriber to compose mail !');
        return false;
    }
    else {
        return confirm('Do you want to proceed?');
    }
}
</script>
@endpush