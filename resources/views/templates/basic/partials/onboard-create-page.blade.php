@extends('admin.layouts.master') @section('content') 
<div class="login-mains p-0">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 text-center p-0 mt-sm-3 mb-2">
        <div class="card px-4 pb-0 mt-3 mb-3">
          <div id="msform">
            <!-- progressbar -->
            <ul id="progressbar">
              <li class="active" id="account">
                <strong>Create page</strong>
              </li>
              <li id="personal">
                <strong>Impact configuration</strong>
              </li>
              <li id="payment" class="hidden">
                <strong>KYC</strong>
              </li>
              <li id="confirm">
                <strong>Submit</strong>
              </li>
            </ul>
            <div class="progress">
              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:25%"></div>
            </div>
            <br>
            <!-- fieldsets -->
            <fieldset id="formFieldset">
              <div class="form-card">
                <div class="row">
                  <div class="col-7">
                    <h2 class="fs-title">Create a page:</h2>
                  </div>
                  <div class="col-5">
                    <h2 class="steps" id="steps">Step 1 - 3</h2>
                  </div>
                </div>
                <form class="action-form" enctype="multipart/form-data" id="createPageForm"> @csrf @php $pageTitle = 'Create New Waqf Page'; $categories = \App\Models\Category::active()->orderBy('id', 'DESC')->get(); @endphp <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label >@lang('Select Category')</label>
                        <div class="input-group mb-3">
                          <span class="input-group-text">
                            <i class="fas fa-align-justify"></i>
                          </span>
                          <select id="categorySelect" name="category_id" class="form-control form--control" required>
                            <option value="" disabled selected>@lang('Select One')</option> @foreach ($categories as $category) <option value="{{ $category->id }}" data-custom-attribute="{{ $category->is_corporate }}" @selected(old('category_id')==$category->id)> {{ __($category->name) }}
                            </option> @endforeach
                          </select>
                        </div>
                      </div>
                      <p id="charityNote" style="color:orange;display: none;margin-bottom:10px;"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Select this only if you are a charity.</p>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>@lang('Goal Amount')</label>
                        <div class="input-group mb-3">
                          <span class="input-group-text">{{ $general->cur_sym }}</span>
                          <input type="number" step="any" name="goal" value="{{ old('goal') }}" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>@lang('Title')</label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="fas fa-heading"></i>
                          </span>
                          <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>@lang('Target Date')</label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="far fa-clock"></i>
                          </span>
                          <input name="deadline" type="text" data-language="en" class="datepicker-here form-control" data-position='bottom left' autocomplete="off" value="{{ old('deadline') }}" required>
                        </div>
                        <div class="d-flex align-items-center gap-3 mt-3">
                          <input style="width: 20px;height: 20px;accent-color: #1e7176;"type="checkbox" value="12/12/2100" checked>
                          <label for="deadline" class="mb-0"> @lang('No target date').</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="d-flex text-left">@lang('Banner Image')</label>
                        <div class="input-group" id="bannerImage">
                          <span class="input-group-text">
                            <i class="fas fa-images"></i>
                          </span>
                          <input type="file" name="image" id="inputAttachments" class="form-control" accept="image/*" required />
                        </div>
                        <div id="storedBannerImg" style="display: none;flex-direction: column;">
                           <img id="bannerImageView" alt="Banner Image" style="max-width: max-content;margin-bottom: 16px;">
                            <button class="btn cmn-btn" onclick="changeImage()" style="max-width: max-content;font-size: 14px;">Change</button>
                        </div>  
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="document-file">
                        <div class="document-file__input">
                          <div class="form-group">
                            <label class="d-flex text-left">@lang('Additional Images and Supporting Documents')</label>
                            <input type="file" name="attachments[]" id="inputAttachments" class="form-control mb-2" accept=".jpg, .jpeg, .png, .pdf" required />
                          </div>
                          <!-- form-group end -->
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                          <button type="button" class="btn cmn-btn add-new d-flex align-items-center justify-content-center" style="height: 30px;width: 30px;">
                            <i class="fa fa-plus me-0"></i>
                          </button>
                          <div id="fileUploadsContainer"></div>
                          <small class="text-muted mb-0"> @lang('Allowed File Types: .jpg, .jpeg, .png, .pdf') </small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="d-flex text-left">@lang('Description') <span class="text-danger"></span>
                    </label>
                    <textarea class="form-control mytextarea" name="description" rows="8" id="description">{{ old('description') }}</textarea>
                    <small class="d-flex text-left" style="color:orange">@lang('It should be more than 200 Characters, a lengthy text that describes why the campaign was created').</small>
                    <div style="font-size: 12px;">Entered Characters: <span id="charCount">0</span></div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                    <button class="btn btn-outline-primary backButton" id="cancelButton" style="height: 50px;background: transparent;border: 1px solid #12525f;color:#12525f;"> Cancel</button>
                    <button class="btn cmn-btn" type="submit">@lang('Next') </button>
                  </div>
                </form>
              </div>
              <button class="firstNext next" style="visibility:hidden;"></button>
            </fieldset>
            <fieldset id="impactFieldSet">
              <div class="form-card">
                <div class="row">
                  <div class="col-7">
                    <h2 class="fs-title">Impact configuration:</h2>
                  </div>
                  <div class="col-5">
                    <h2 class="steps">Step 2 - 3</h2>
                  </div>
                </div>
                <div class="row py-4">
                  <h3 class="mb-4" style="color: #1e7176;">Donation Categories</h3>
                  <p class="mb-3" style="color:#000;font-size: 16px;">Donations are invested in income generated projects, to maximise benefits and make everlasting significant impact. You can choose the categories categories of donations to receive on your page, the returns will be spent on projects across the categories</p>
                  <p class="mb-4" style="color:#000;font-size: 16px;">Please take note that the categories you choose will allow donors to dedicate their donations to only one option.</p>
                  <div>
                  <form id="impactConfigurationForm">
                      @csrf
                      @php
                          $categories = \App\Models\DonationCategory::searchable(['name'])->orderBy('name')->paginate(getPaginate());
                      @endphp

                      <div class="d-flex align-items-center gap-3 flex-wrap" style="padding-bottom: 30px; border-bottom: 1px solid #eee;" id="aboveCheckboxes">
                          @foreach ($categories as $category)
                                @if ($category->id !== 10)
                                  <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap" style="background-color: #1e7176; padding: 1rem; color: #fff; border-radius: 6px;">
                                      <input type="checkbox" name="donationCategories[]" id="category{{ $category->id }}" value="{{ $category->id }}" style="width: 20px; height: 20px; accent-color: #1e7176"  onchange="uncheckWaqfinity()">
                                      <label for="category{{ $category->id }}" class="mb-0">{{ __($category->name) }}</label>
                                  </div>
                              @endif
                          @endforeach
                      </div>

                      <div>
                          <div class="d-flex align-items-start justify-content-start gap-2 my-3" id="waqCategory">
                              @foreach ($categories as $category)
                                     @if ($category->id == 10)
                                      <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap" style="padding: 1rem 1rem 1rem 0; color: #fff; border-radius: 6px; max-width: max-content;">
                                          <input type="checkbox" name="donationCategories[]" checked id="category{{ $category->id }}" value="{{ $category->id }}" style="width: 20px; height: 20px; accent-color: #1e7176" onchange="uncheckAll()">
                                          <label for="category{{ $category->id }}" class="mb-0" style="color: #000; font-size: 16px; font-weight: 400;"> Let Waqfinity decide</label>
                                      </div>
                                  @endif
                              @endforeach
                          </div>
                      </div>

                      <div class="d-flex align-items-center justify-content-between mt-5">
                          <button class="btn btn-outline-primary" id="impactBack" style="height: 50px; background: transparent; border: 1px solid #12525f; color: #12525f;"> Back</button>
                          <button class="btn cmn-btn" type="submit">@lang('Next')</button>
                      </div>
                  </form>
                  </div>
                </div>
              </div>
              <button class="secondPrevious previous" style="visibility:hidden;"></button>
              <button class="secondNext next" style="visibility:hidden;"></button>
            </fieldset>
            <fieldset id="kycFieldSet">
              <div class="form-card">
                <div class="row">
                  <div class="col-7">
                    <h2 class="fs-title">KYC:</h2>
                  </div>
                  <div class="col-5">
                    <h2 class="steps">Step 2 - 3</h2>
                  </div>
                </div>
                <form enctype="multipart/form-data" id="kycForm"> @csrf
                  <x-viser-form identifier="act" identifierValue="kyc" />
                  <div class="d-flex align-items-center justify-content-between">
                    <button class="btn btn-outline-primary backButton" id="kycBack" style="height: 50px;background: transparent;border: 1px solid #12525f;color:#12525f;"> Back</button>
                    <button type="submit" class="btn cmn-btn">@lang('Next')</button>
                  </div>
                </form>
              </div>
              <button class="thirdPrevious previous" style="visibility:hidden;"></button>
              <input type="button" name="next" class="next kycNext action-button" style="visibility: hidden;" />
            </fieldset>
            <fieldset id="onboardFieldset">
              <div class="form-card">
                <div class="row">
                  <div class="col-7">
                    <h2 class="fs-title">Submit:</h2>
                  </div>
                  <div class="col-5">
                    <h2 class="steps">Step 3 - 3</h2>
                  </div>
                </div>
                <div class="row">
                  <div class="px-3 py-4">
                    <h3 class="mb-2">Login</h3>
                    <p>Click here to <a href="{{ url('/user/login') }}">Login</a>. If you already have an account. </p>
                  </div>
                  <div class="px-3 py-4">
                    <h3 class="mb-2">Register</h3>
                    <p>Don't have an account.Click here to <a href="{{ url('/user/register') }}">Register</a>. </p>
                  </div>
                </div>
                <div class="mt-4">
                  <button class="btn btn-outline-primary backButton" id="kycBack" style="height: 50px;background: transparent;border: 1px solid #12525f;color:#12525f;" onclick="submitGoBack()">Back</button>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> 
@endsection @push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}"> @endpush 
@push('script-lib') 
<script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css'>
<script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script> 
 <script src="https://cdn.tiny.cloud/1/05re6t76574jaj7vnjs1gwcvdgzxf0abxsr14c44a30wjk75/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush 
@push('script') 
<script>
  'use strict';
  $(".add-new").on('click', function() {
    $("#fileUploadsContainer").append(` 
                           
                              
                  
                  <div class="input-group mb-2">
                    <input type="file" name="attachments[]" id="inputAttachments" class="form-control" accept=".jpg, .jpeg, .png, .pdf" required/>
                    <button type="button" class="input-group-text btn--danger remove-btn">
                      <i class="las la-times"></i>
                    </button>
                  </div>
                `);
  })
  $(document).on('click', '.remove-btn', function() {
    $(this).closest('.input-group').remove();
  });

  document.addEventListener('DOMContentLoaded', function () {
    tinymce.init({
        selector: '.mytextarea',
        plugins: 'fontSize bold',
        toolbar: 'undo redo | formatselect | alignleft aligncenter alignright alignjustify | bold italic forecolor backcolor | fontSize',
        menubar: false, // Hide the menubar
        init_instance_callback: function (editor) {
            // Initial character count
            updateCharCount(editor);

            // Update character count on every keyup event
            editor.on('keyup', function () {
                updateCharCount(editor);
            });
        }
      });

    function updateCharCount(editor) {
          // Get the content of the editor
          var content = editor.getContent({ format: 'text' });

          // Count the characters
          var charCount = content.length;

          // Display the character count
          document.getElementById('charCount').innerText = charCount;

          // Check if the minimum character count is met
          if (charCount >= 200) {
              // Reset the alert
              document.getElementById('charCount').classList.remove('belowMin');
          } else {
              // Show alert only if the character count is below 200
              document.getElementById('charCount').classList.add('belowMin');
          }
      }
  });


  $('.datepicker-here').on('keyup keypress keydown input',function(){
      $('input[type="checkbox"]').prop('checked', false);
      return false;
  });

  var currentDate = new Date();
  currentDate.setMonth(currentDate.getMonth() + 1);
  $('.datepicker-here').datepicker({
    minDate: currentDate
  });

</script>
<script>
  $(document).ready(function() {
    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    var current = 1;
    var steps = $("fieldset").length;
    $(".previous").click(function() {
      current_fs = $(this).parent();
      previous_fs = $(this).parent().prev();
      //Remove class active
      $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
      //show the previous fieldset
      previous_fs.show();
      //hide the current fieldset with style
      current_fs.animate({
        opacity: 0
      }, {
        step: function(now) {
          // for making fielset appear animation
          opacity = 1 - now;
          current_fs.css({
            'display': 'none',
            'position': 'relative'
          });
        },
        duration: 500
      });
    });

    $(".submit").click(function() {
      return false;
    })
  });
</script>
<script>
  let customAttribute = null;
  const categorySelect = document.getElementById('categorySelect');
  const charityNote = document.getElementById('charityNote');
  categorySelect.addEventListener('change', function() {
    const selectedOption = categorySelect.options[categorySelect.selectedIndex];
    // Get the value of the custom attribute
    customAttribute = selectedOption.getAttribute('data-custom-attribute');
    localStorage.setItem('customAttribute', customAttribute);
    const confirmTag= document.getElementById('personal');
    const kycTag = document.getElementById('payment');
    var stepsElement = document.getElementById('steps');
     if (customAttribute == 0) {
        if (stepsElement) {
            // Change the text content
            stepsElement.textContent = '1 - 3';
        }
        kycTag.classList.add('hidden');
        confirmTag.classList.remove('hidden');
     } 
     else{
        if (stepsElement) {
            // Change the text content
            stepsElement.textContent = '1 - 3';
        }
         confirmTag.classList.add('hidden');
         kycTag.classList.remove('hidden');
     }
    if (categorySelect.value == 4) {
      charityNote.style.display = 'block';
    } else {
      charityNote.style.display = 'none';
    }
  });


  function submitGoBack(){
     const confirmTag= document.getElementById('confirm');
     const paymentag= document.getElementById('payment');
     const personatag= document.getElementById('personal');
     confirmTag.classList.remove('active');
     let attribute  = localStorage.getItem('customAttribute');
     console.log('att', attribute)
    if (attribute == 0) {
        $(".progress-bar").css("width", "50%");
        $("#onboardFieldset").css("display", "none");
        $("#kycFieldSet").css("display", "none");
        $("#impactFieldSet").css("display", "block");
        personatag.classList.add('active');
    }
    else{
      $(".progress-bar").css("width", "50%");
      $("#onboardFieldset").css("display", "none");
      $("#impactFieldSet").css("display", "none");
      $("#kycFieldSet").css("display", "block");
      paymentag.classList.add('active');
    }

  }
  document.getElementById('createPageForm').addEventListener('submit', function(e) {

    var content = tinyMCE.activeEditor.getContent({ format: 'text' });
    var charCount = content.length;
    if (charCount < 200) {
        e.preventDefault();
        alert('Minimum 200 characters required!');
    }
    else{
      tinymce.triggerSave();
      const formData = new FormData(this);
      e.preventDefault(); // Prevent the default form submission
      // Convert the form data to a JavaScript object
      const formObject = {};
      formData.forEach((value, key) => {
        if (this.elements[key].type === 'file') {
              storeImageInSessionStorage(this.elements[key], key);
          } else {
              // If it's not a file input, store the value in the formObject
              formObject[key] = value;
          }
      });

      function storeImageInSessionStorage(fileInput, key) {
          const imageFile = fileInput.files[0];

          if (imageFile) {
              const reader = new FileReader();
              reader.onload = function (e) {
                  const base64String = e.target.result;

                  // Store the image data in session storage with the specified key
                  sessionStorage.setItem(key, base64String);
              };

              reader.readAsDataURL(imageFile);
          }
      }

      // Clear the onboardingProcess data in local storage
      localStorage.removeItem('createPageData');
      // Save the form data to local storage
      localStorage.setItem('createPageData', JSON.stringify(formObject));
      const savedFormData = localStorage.getItem('createPageData');
      if (savedFormData) {
        console.log(savedFormData);
        localStorage.setItem('onboardingProcess', 'createPage');
        const formDataObject = JSON.parse(savedFormData);
        const pageFieldSet = document.getElementById('formFieldset');
        const impactFieldSet = document.getElementById('impactFieldSet');
        const personal = document.getElementById('personal');
        const kyc = document.getElementById('payment');
        const kycFieldSet = document.getElementById('kycFieldSet');
        // Hide the fieldset using CSS class instead of direct style manipulation
        pageFieldSet.style.display = 'none';
        pageFieldSet.style.position = 'relative';
        personal.classList.add("active");
        $('html, body').animate({
          scrollTop: 0
        }, 500);
        let attribute  = localStorage.getItem('customAttribute');
        console.log('att', attribute);
        if (attribute == 0) {
          $(".progress-bar").css("width", "50%");
          impactFieldSet.style.display = 'block';
          impactFieldSet.style.position = 'relative';
          impactFieldSet.style.opacity = '1';
        } else {
          kyc.classList.add("active");
          $(".progress-bar").css("width", "50%");
          kycFieldSet.style.display = 'block';
          kycFieldSet.style.position = 'relative';
        }
      }

    }
  });


  document.getElementById('impactConfigurationForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission
    // Create an array to store selected category IDs
    const selectedCategories = [];
    // Loop through all checkboxes
    document.querySelectorAll('input[name="donationCategories[]"]:checked').forEach(function(checkbox) {
      selectedCategories.push(checkbox.value);
    });
    // Convert the form data to a JavaScript object
    const formObject = {};
    const formData = new FormData(this);
    formData.forEach((value, key) => {
      formObject[key] = value;
    });
    // Add the selected categories to the formObject
    formObject['selectedCategories'] = selectedCategories;
    // Clear the onboardingProcess data in local storage
    localStorage.removeItem('impactConfigurationFormData');
    // Save the form data to local storage
    localStorage.setItem('impactConfigurationFormData', JSON.stringify(formObject));
    const savedFormData = localStorage.getItem('impactConfigurationFormData');
    if (savedFormData) {
      localStorage.setItem('onboardingProcess', 'impactConfigaration');
      const formDataObject = JSON.parse(savedFormData);
      const impactFieldSet = document.getElementById('impactFieldSet');
      const onboardFieldset = document.getElementById('onboardFieldset');
      impactFieldSet.style.display = 'none';
      impactFieldSet.style.position = 'relative';
      const kyc = document.getElementById('payment');
      kyc.classList.add("active");
      const confirm = document.getElementById('confirm');
      confirm.classList.add("active");
      $(".progress-bar").css("width", "100%");
      onboardFieldset.style.display = 'block';
      onboardFieldset.style.position = 'relative';
    }
  });
  document.getElementById('kycForm').addEventListener('submit', function(e) {
    const formData = new FormData(this);
    e.preventDefault(); // Prevent the default form submission
    const formObject = {};
    formData.forEach((value, key) => {
      if (this.elements[key].type === 'file') {
        storeFileInSessionStorage(this.elements[key], key);
      } else {
        // If it's not a file input, store the value in the formObject
        formObject[key] = value;
      }
    });

    function storeFileInSessionStorage(fileInput, key) {
      const uploadedFile = fileInput.files[0];

      if (uploadedFile) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const base64String = e.target.result;

          // Store the file data in session storage with the specified key
          sessionStorage.setItem(key, base64String);
        };

        // Read the file as a Data URL
        reader.readAsDataURL(uploadedFile);
      }
    }

    localStorage.removeItem('kycData');
    // Save the form data to local storage
    localStorage.setItem('kycData', JSON.stringify(formObject));
    const savedFormData = localStorage.getItem('kycData');
    console.log('kyc',savedFormData);
    if (savedFormData) {
      localStorage.setItem('onboardingProcess', 'kyc');
      const formDataObject = JSON.parse(savedFormData);
      const kycFieldSet = document.getElementById('kycFieldSet');
      kycFieldSet.style.display = 'none';
      kycFieldSet.style.position = 'relative';
      $(".progress-bar").css("width", "100%");
      const confirm = document.getElementById('confirm');
      confirm.classList.add("active");
      onboardFieldset.style.display = 'block';
      onboardFieldset.style.position = 'relative';
    }
  });
</script>
<script>
  document.getElementById('cancelButton').addEventListener('click', function(e) {
    e.preventDefault();
    window.history.back();
  });
  document.getElementById('impactBack').addEventListener('click', function(e) {
    e.preventDefault();
    $(".secondPrevious").click();
    $(".progress-bar").css("width", "25%");
     const personal = document.getElementById('personal');
     const kyc = document.getElementById('payment');
     kyc.classList.remove("active");
     personal.classList.remove("active");
  });
  document.getElementById('kycBack').addEventListener('click', function(e) {
    e.preventDefault();
      const personal = document.getElementById('personal');
      const kyc = document.getElementById('payment');
      const kycFieldSet = document.getElementById('kycFieldSet');
      kycFieldSet.style.display = 'none';
      kycFieldSet.style.position = 'relative';
      $(".secondPrevious").click();
      $(".progress-bar").css("width", "25%");
      kyc.classList.remove("active");
      personal.classList.remove("active");
  });
    document.addEventListener('DOMContentLoaded', function () {
      // Check if there is an image in sessionStorage
      const storedImage = sessionStorage.getItem('image');
      const inputAttachments = document.getElementById('inputAttachments');

      if (storedImage) {
          document.getElementById('bannerImage').style.display = 'none';
          document.getElementById('bannerImageView').src = storedImage;
          document.getElementById('storedBannerImg').style.display = 'flex';
          inputAttachments.removeAttribute('required');
      }
  });

  function changeImage() {
      const inputAttachments = document.getElementById('inputAttachments');
      document.getElementById('bannerImage').style.display = 'flex';
      document.getElementById('storedBannerImg').style.display = 'none';
      document.getElementById('bannerImageView').src = '';
      inputAttachments.setAttribute('required', 'required');
  }
</script>
<script>
    function uncheckAll() {
        const aboveCheckboxes = document.getElementById('aboveCheckboxes');
        const checkboxes = aboveCheckboxes.querySelectorAll('input[name="donationCategories[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }    

    function uncheckWaqfinity() {
        const waqCategory = document.getElementById('waqCategory');
        const checkboxes = waqCategory.querySelectorAll('input[name="donationCategories[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    $(document).ready(function() {
    // Check the initial state of the checkbox
    updateDeadlineValue();

    // Attach an event listener to the checkbox
    $('input[type="checkbox"]').change(function() {
      updateDeadlineValue();
    });

    $('.datepicker-here').on('click', function() {
      // Uncheck the checkbox when the datepicker input is clicked
      $('input[type="checkbox"]').prop('checked', false);
    });

    function updateDeadlineValue() {
      // Get the checkbox state
      var checkboxChecked = $('input[type="checkbox"]').prop('checked');

      // Set the deadline value based on the checkbox state
      if (checkboxChecked) {
        $('input[name="deadline"]').val('12/12/2100');
      } else {
        // Use the value from the datepicker
        // You may need to adjust this based on the actual value format returned by the datepicker
        $('input[name="deadline"]').val($('.datepicker-here').val());
      }
    }
  });
</script>
<script>
  const onboardingCompleted = localStorage.getItem('onboardingProcess');
  if (onboardingCompleted === null) {
    localStorage.setItem('onboardingProcess', 'true');
  }
</script> @endpush @push('style') <style>
  fieldset {
    opacity: 1;
    transition: opacity 0.5s ease-in-out;
  }

  fieldset.hidden, li.hidden {
    opacity: 0;
    display: none;
  }

  fieldset.visible, li.visible {
    opacity: 1;
    display: block !important;
  }
  .hideProgress li{
     width: 33% !important;
  }
  body .tox-tinymce:hover,
  body .tox-tinymce:focus {
    border: 1px solid #4634ff !important;
    box-shadow: 0 3px 9px rgba(50, 50, 9, 0.05), 3px 4px 8px rgba(115, 103, 240, 0.1) !important;
  }
  body .tox-statusbar__branding{
    display: none !important;
  }
  /* Apply the overlay to the container element */
.login-mains {
    position: relative;
}

/* Create the overlay */
.login-mains::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(30, 113, 118, 0.5); /* Adjust the color and opacity here */
    z-index: 1; /* Ensure the overlay is on top of the background image */
}

/* Original background styles */
.login-mains {
    background-color: #1E7176;
    background-image: url(https://app.waqfinity.com/assets/templates/basic/images/texture-3.jpg);
    background-size: cover;
    width: 100%;
    min-height: 100vh;
}
.container{
  position: relative;
  z-index: 9;
}
body .tox-tinymce {
    border: 1px solid #eee !important;
    transition: border 0.2s ease !important;
  }
body .tox-statusbar {
    display: none !important;
  }
#charCount.belowMin{
    color: red;
    font-weight: bold;
}

@media(max-width: 480px){

  #progressbar li {
      font-size: 12px;
  }

  .fs-title , .steps{
     font-size: 14px;
  }

  .cmn-btn, .backButton , .btn-outline-primary{
    height: 40px !important;
  }

  h3{
    font-size: 1rem;
  }

}
</style> 
@endpush


