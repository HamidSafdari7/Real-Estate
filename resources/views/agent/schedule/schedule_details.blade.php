@extends('agent.agent_dashboard')
@section('agent')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>



<div class="page-content">

				<nav class="page-breadcrumb">
					<ol class="breadcrumb">
						
					</ol>
				</nav>

				<div class="row">
					<div class="col-md-12">
            <div class="card">

            	<form method="POST" action="{{ route('agent.update.schedule') }}">

            		@csrf

                <input type="hidden" name="id" value="{{ $schedule->id }}">

                <input type="hidden" name="email" value="{{ $schedule->user->email }}">


                <div class="table-responsive">
                  <table class="table table-bordered">
                    
                    <tbody>


                      <tr>
                        <td>User</td>
                        <td>{{$schedule['user']['name']}}</td>
                        
                      </tr>


                      <tr>
                        <td>Property Name</td>
                        <td>{{$schedule['property']['property_name']}}</td>
                        
                      </tr>


                      <tr>
                        <td>Tour Date</td>
                        <td>{{$schedule->tour_date}}</td>
                        
                      </tr>


                      <tr>
                        <td>Tour Time</td>
                        <td>{{$schedule->tour_time}}</td>
                        
                      </tr>


                      <tr>
                        <td>Message</td>
                        <td>{{$schedule->message}}</td>
                        
                      </tr>


                      <tr>
                        <td>Request Time</td>
                        <td>{{$schedule->created_at->format('l M d Y')}}</td>
                        
                      </tr>




                      
                    </tbody>
                  </table>
                </div>


                <br><br>
                <div class="d-flex justify-content-center align-items-center">
                  <button type="submit" class="btn btn-success">Confirm Request</button>
                </div>
                <br><br>
              
            
              </form>

            </div>

					</div>
				</div>
			</div>



@endsection