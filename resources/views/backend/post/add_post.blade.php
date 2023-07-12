@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

  <div class="page-content">

        
        <div class="row profile-body">
          
          <!-- middle wrapper start -->
          <div class="col-md-12 col-xl-12 middle-wrapper">
            <div class="row">
              
              <div class="card">
              <div class="card-body">

                <h6 class="card-title">Add Post</h6>

                <form class="forms-sample" method="POST" action="{{ route('store.post') }}"
                enctype="multipart/form-data">

                @csrf

                  

                      <div class="row">
                              <div class="col-sm-6">
                                  <div class="form-group mb-3">
                                      <label class="form-label">Post Title</label>
                                      <input type="text" name="post_title" class="form-control" >
                                  </div>
                              </div><!-- Col -->


                              <div class="col-sm-6">
                                  <div class="form-group mb-3">
                                      <label class="form-label">Blog Category</label>
                                          <select name="blogcat_id" class="form-select" id="exampleFormControlSelect1">
                                              <option selected="" disabled="">Select Category</option>
                                              @foreach($blogcat as $cat)
                                              <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                              @endforeach
                                          </select>
                                  </div>
                              </div><!-- Col -->

                      </div>




                      <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Short Description</label>
                                        <textarea name="short_descp" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                    </div>
                                </div><!-- Col -->


                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label class="form-label">Long Description</label>
                                            <textarea class="form-control" name="long_descp" id="tinymceExample" rows="10"></textarea>
                                        </div>
                                    </div><!-- Col -->



                                    <div class="col-sm-6">
                                      <div class="form-group mb-3">
                                          <label class="form-label">Post Tags</label>
                                          <input name="post_tags" id="tags" value="Real Estate,">
                                      </div>
                                  </div><!-- Col -->




                  
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Post Image</label>
                    <input type="file" name="post_image" class="form-control" id="image">
                  </div>

                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label"></label>
                    <img id="showImage" class="wd-80 rounded-circle" src="{{url('upload/no_image.jpg')}}" alt="profile" style="height:80px;">
                  </div>
                  

                

                  <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                  
                </form>

              </div>
            </div>
              
            </div>
          </div>
          <!-- middle wrapper end -->
          <!-- right wrapper start -->
          
          <!-- right wrapper end -->
        </div>

  </div>

<script type="text/javascript">
  
  $(document).ready(function(){

    $('#image').change(function(e){

      var reader = new FileReader();

      reader.onload = function(e){

        $('#showImage').attr('src',e.target.result);
      }

      reader.readAsDataURL(e.target.files[0]);
    });
  });

</script>


@endsection