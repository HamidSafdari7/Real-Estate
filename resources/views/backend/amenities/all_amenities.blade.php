@extends('admin.admin_dashboard')
@section('admin')


<div class="page-content">

				<nav class="page-breadcrumb">
					<ol class="breadcrumb">
            @if(Auth::user()->can('amenities.add'))
						<a href="{{ route('add.amenitie') }}" class="btn btn-inverse-info">Add Amenities</a>
            @endif
					</ol>
				</nav>

				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h6 class="card-title">All Amenities</h6>
                
                <div class="table-responsive">
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>Serial Num</th>
                        <th>Amenitie Name</th>
                        
                        <th>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                	@foreach($amenities as $key => $item)
                      <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->amenities_name   }}</td>
                        
                        <td>
                          @if(Auth::user()->can('amenities.edit'))
                        	<a href="{{ route('edit.amenitie',$item->id) }}" class="btn btn-inverse-warning">Edit</a>
                          @endif

                          @if(Auth::user()->can('amenities.delete'))
                        	<a href="{{ route('delete.amenitie',$item->id) }}" class="btn btn-inverse-danger" id="delete">Delete</a>
                          @endif
                        </td>
                        
                      </tr>
                     @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
					</div>
				</div>

			</div>


@endsection