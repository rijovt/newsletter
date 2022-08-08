@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Compose E-mail</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('sendMail') }}" onsubmit="return valid();">
                        @csrf
                            <input type="hidden" name="selectedSubscribers" value="{{ $selectedSubscribers }}">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="subject">Email Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="message">Email Content</label>
                                <textarea id="message" name="message" class="col-md-10 form-control tinymce"></textarea>
                            </div>
                            <div class="form-group row">
                                <button type="submit" class="btn btn-sm btn-success">
                                    {{ __('Send Mail') }}
                                </button>                                  
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  
<script>tinymce.init({selector:'textarea.tinymce'});</script>
<script>
function valid() {
    if($('#subject').val()== ''){
        alert('Please enter email subject.')
        return false;
    }    
    else if(tinymce.activeEditor.getBody().textContent== ''){
        alert('Please enter a mail content.')
        return false;
    }
    else {
        return confirm('Are you sure to send mail?');
    }
}
</script>
@endpush