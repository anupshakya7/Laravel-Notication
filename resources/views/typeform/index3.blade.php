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
    #check_country{
        display: none;
        position: absolute;
        background: #6c5f5f5c;
        color: #000000;
        padding: 5px 10px;
        width: 300px;
        right: 0;
        top: 48px;
        transition: 0.5sease-in-out;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
    <div class="card p-3 mt-4">
        <h4>Typeform Add Country and State</h4>
        <form action="{{route('typeform.form3.edit')}}" method="POST">
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
               {{-- <input type="checkbox" name="fields[region]" id="region"> <label for="region">Region</label> <br> --}}
               <input type="checkbox" name="fields[country]" id="country"> <label for="country">Country</label> <br>
               <input type="checkbox" name="fields[state]" id="state" disabled> <label for="state">State</label> <br>
               <p style="display:inline-block;border-radius:5px;color:#fff;padding:4px 10px;font-size:14px;font-weight:500;margin-top:10px;background-color:rgb(142, 15, 15);box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;">For State you should check country first</p>
            </div>      
            <div class="my-3">
                <div id="addBtnWrapper" style="position: relative;">
                    <button type="submit" class="btn btn-primary float-end mx-1" id="addBtn" disabled>Add Fields</button>
                    <span id="check_country">You should choose Country Checkbox</span>
                </div>
               
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

            //Country Checkbox
            $('#country').change(function(){
                checkCountryCheckBox();
            });
            checkCountryCheckBox();

            function checkCountryCheckBox(){
                if($('#country').is(':checked')){
                    $('#state').attr('disabled',false);
                    $('#check_country').css('display','none');
                }else{
                    $('#state').attr('disabled',true);
                    $('#check_country').css('display','block');
                }
            }
           
        });
    </script>
@endpush
