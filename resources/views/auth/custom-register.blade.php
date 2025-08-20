@extends('layouts.custom_auth')

@section('content')
<div class="bg-white rounded10 shadow-lg">
    <div class="content-top-agile p-20 pb-0">
        <h2 class="text-primary fw-600">Get started with Us</h2>
        <p class="mb-0 text-fade">Register a new membership</p>
    </div>
    <div class="p-40">
        <form action="{{ route('custom_register.submit', $organization->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="text-fade ti-user"></i></span>
                    <input type="text" class="form-control ps-15 bg-transparent" placeholder="Full Name" name="name" required>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="text-fade ti-email"></i></span>
                    <input type="email" name="email" required class="form-control ps-15 bg-transparent" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
                    <input type="text" name="phone" required class="form-control ps-15 bg-transparent" placeholder="Phone">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="reg-radio-button">
                        <input name="verification_type" value="aadhaar" required type="radio" id="aadhaar" class="radio-col-primary" checked="">
                        <label for="aadhaar">Aadhar</label>
                        <input name="verification_type" value="emirates_id" type="radio" id="emirates_id" class="radio-col-success">
                        <label for="emirates_id">Emirates ID</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="text-fade ti-lock"></i></span>
                    <input type="text" id="verification_id_number" name="verification_id_number" required class="form-control ps-15 bg-transparent" placeholder="Verification ID Number">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="text-fade ti-image"></i></span>
                    <input type="file" name="verification_image" required class="form-control ps-15 bg-transparent" placeholder="Upload Verification Image">
                </div>
            </div>
            <div class="row">
                <!-- <div class="col-12">
                    <div class="checkbox">
                        <input type="checkbox" id="basic_checkbox_1">
                        <label for="basic_checkbox_1">I agree to the <a href="#" class="text-primary">Terms</a></label>
                    </div>
                </div> -->
                <!-- /.col -->
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary w-p100 mt-10">REGISTER</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <div class="text-center">
            <p class="mt-15 mb-0 text-fade">Already have an account?<a href="{{ route('member_login', $organization->slug) }}" class="text-primary ms-5">Sign In</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const radios = document.querySelectorAll("input[name='verification_type']");
        const idField = document.getElementById("verification_id_number");

        radios.forEach(radio => {
            radio.addEventListener("change", function() {
                idField.value = ""; // clear on change
                if (this.value === "aadhaar") {
                    idField.setAttribute("placeholder", "XXXX XXXX XXXX");
                    idField.setAttribute("maxlength", "14"); // incl. spaces
                } else if (this.value === "emirates_id") {
                    idField.setAttribute("placeholder", "###-####-#######-#");
                    idField.setAttribute("maxlength", "18"); // incl. dashes
                }
            });
        });

        idField.addEventListener("input", function() {
            let selected = document.querySelector("input[name='verification_type']:checked")?.value;

            if (selected === "aadhaar") {
                let v = this.value.replace(/\D/g, "").substring(0, 12);
                v = v.replace(/(\d{4})(\d{4})(\d{0,4})/, function(_, a, b, c) {
                    return [a, b, c].filter(Boolean).join(" ");
                });
                this.value = v;
            } else if (selected === "emirates_id") {
                let v = this.value.replace(/\D/g, "").substring(0, 15);
                v = v.replace(/(\d{3})(\d{4})(\d{7})(\d{0,1})/, function(_, a, b, c, d) {
                    return [a, b, c, d].filter(Boolean).join("-");
                });
                this.value = v;
            }
        });
    });
</script>
@endpush