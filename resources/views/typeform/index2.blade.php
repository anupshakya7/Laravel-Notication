@extends('typeform.layout.web')
@push('style')
<style>
    .select2-container--default .select2-selection--single{
        height: 35px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        height: 35px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 35px;
    }
</style>
@endpush

@section('content')
    <div class="card p-3 mt-4">
        <h4>Typeform Add Country and State</h4>
        <form action="{{route('typeform.form2.edit')}}" method="POST">
            @csrf
            <div class="my-3">
                <label for="formId" class="form-label">Form Id</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="formId" id="formId" placeholder="Form Id">
                <span id="form_message"></span>
            </div>
            <div id="form_details">
                <input type="hidden" name="typeform_data" id="typeform_data">
               <label for="">Select Fields For Form</label> 
               <br>
               <input type="checkbox" name="fields[]" value="region" id="region"> <label for="region">Region</label> <br>
               <input type="checkbox" name="fields[]" value="country" id="country"> <label for="country">Country</label> <br>
               <input type="checkbox" name="fields[]" value="state" id="state"> <label for="state">State</label> <br>
            </div>      
            <div class="my-3">
                <button type="submit" class="btn btn-primary float-end mx-1" id="addBtn" disabled>Add Fields</button>
                <button type="button" class="btn btn-primary float-end mx-1" id="sync_form">Sync Form</button>
            </div>
           
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
                        console.log('success'+response);
                        $('#form_message').text('Successfully Sync!!!');
                        $('#form_message').removeClass('text-danger');
                        $('#form_message').addClass('text-success');
                        $('#formId').attr('disabled',true);
                        $('#typeform_data').val(JSON.stringify(response));
                        $('#addBtn').attr('disabled',false);
                    },
                    error: function(err){
                        $('#form_message').text('Failed to Sync');
                        $('#form_message').removeClass('text-success');
                        $('#form_message').addClass('text-danger');
                        $('#formId').val('');
                        $('#formId').attr('disabled',false);
                        $('#addBtn').attr('disabled',true);
                    }
                })
            });
        })
    </script>
@endpush
