@extends('typeform.layout.web')
@section('content')
    <div class="card p-3 mt-4">
        <h4>Typeform Add Country and State</h4>
        <form action="{{route('typeform.form.edit')}}" method="POST">
            @csrf
            <div class="my-3">
                <label for="formId" class="form-label">Form Id</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="formId" id="formId" placeholder="Form Id">
                <span id="form_message"></span>
            </div>
            <div id="form_details">
            </div>      
            <button type="submit" class="btn btn-primary float-end mx-1" disabled>Add Country & State</button>
            <button type="button" class="btn btn-primary float-end mx-1" id="sync_form">Sync Form</button>
        </form>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function(){
            $('#sync_form').click(function(){
                let formId = $('#formId').val();
                let baseUrl = "{{url('typeform/getForm')}}";

                $.ajax({
                    url: baseUrl+'/'+formId,
                    type:'GET',
                    success: function(response){
                        console.log(response);
                    },
                    error: function(err){
                        console.log(err);
                    }
                })
            })
        })
    </script>
@endpush
