@extends('layouts.master')

@section('body_content')

<div class="container topety">
  <div class="row">
    <div class="col-md-12">
      <!--breadcrumbs start -->
      <ul class="breadcrumb">
          <li>{{ HTML::link('/', 'Home') }}</li>
          @if($survey->status === 0)
            <li>{{ HTML::link('/survey/add_question/'.$survey->survey_id, 'Add Question') }}</li>
          @endif
      </ul>
      <!--breadcrumbs end -->
    </div>
    <div class="col-md-3">
      <div class="panel panel-default settings-border min-set">
        <div class="panel-body">
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="panel panel-default settings-border min-set">
        <div class="panel-heading">{{$survey->title}}</div>
          <div class="panel-body">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#question" data-toggle="tab">Questions</a></li>
              <li><a href="#settings" data-toggle="tab">Settings</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="question">
                <div class="panel-group toptwen" id="accordion">
                  @foreach ($question as $questions)
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#thequestion{{$questions->question_id}}">
                            {{$questions->question}}
                          </a>
                        </h4>
                      </div>
                      <div id="thequestion{{$questions->question_id}}" class="panel-collapse collapse">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-6">
                              <ul class="list-group">
                                @foreach ($answer as $answers)
                                  @if ($questions->question_type == "text")
                                    <?php 
                                      $new_answer = json_decode($answers->answer); 
                                      $concat = 'question'.$questions->question_id;
                                    ?>
                                      <li class="list-group-item">
                                        {{$new_answer->$concat}}
                                      </li>
                                  @elseif ($questions->question_type == "textarea")
                                    <?php 
                                      $new_answer = json_decode($answers->answer); 
                                      $concat = 'question'.$questions->question_id;
                                    ?>
                                    <li class="list-group-item">
                                      {{$new_answer->$concat}}
                                    </li>
                                  @endif
                                @endforeach
                                @if($questions->question_type == "radio")
                                  <?php 
                                    $theoption = json_decode($questions->option_name);
                                    $chart_data = array();
                                  ?>
                                  @foreach ($theoption as $key => $value)
                                    <?php $key = 0; ?>
                                    @foreach ($answer as $answers)
                                      <?php 
                                        $new_answer = json_decode($answers->answer); 
                                        $concat = 'question'.$questions->question_id;
                                        $value_check = $new_answer->$concat;
                                      ?>
                                      @if($value_check === $value)
                                        <?php $key++; ?>
                                      @endif
                                    @endforeach
                                    @if($key != 0)
                                    <?php 
                                      $present_data = array();
                                      $present_data['value'] = $key;
                                      $present_data['color'] = '#'.dechex(rand(0x000000, 0xFFFFFF));
                                      array_push($chart_data,$present_data);
                                     ?>
                                    @endif
                                    <li class="list-group-item">
                                      <span class="badge">{{$key}}</span>
                                      {{$value}}
                                    </li> 
                                  @endforeach
                                  <br>
                                  <button class="chart btn btn-primary btn-lg" data-chart='{{json_encode($chart_data)}}' data-toggle="modal" data-target="#myModal">
                                    Visualise
                                  </button>
                                @endif
                                @if($questions->question_type == "checkbox")
                                  <?php 
                                    $theoption = json_decode($questions->option_name);
                                    $chart_data = array();
                                  ?>
                                  @foreach ($theoption as $key => $value)
                                    <?php $key = 0; ?>
                                    @foreach ($answer as $answers)
                                      <?php 
                                        $new_answer = json_decode($answers->answer); 
                                        $concat = 'question'.$questions->question_id;
                                        $value_check = $new_answer->$concat;
                                      ?>
                                      @foreach($value_check as $k => $v)
                                        @if($v === $value)
                                          <?php $key++; ?>
                                        @endif
                                      @endforeach
                                    @endforeach
                                    @if($key != 0)
                                    <?php 
                                      $present_data = array();
                                      $present_data['value'] = $key;
                                      $present_data['color'] = '#'.dechex(rand(0x000000, 0xFFFFFF));
                                      array_push($chart_data,$present_data);
                                     ?>
                                    @endif
                                    <li class="list-group-item">
                                      <span class="badge">{{$key}}</span>
                                      {{$value}}
                                    </li>
                                  @endforeach
                                  <br>
                                  <button class="chart btn btn-primary btn-lg" data-chart='{{json_encode($chart_data)}}' data-toggle="modal" data-target="#myModal">
                                    Visualise
                                  </button>
                                @endif
                              </ul>
                            </div>
                            <div class="col-md-6">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
              <div class="tab-pane" id="settings">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-md-8">
              <canvas id="myChart" width="300" height="300"></canvas>
          </div>
          <div class="col-md-4 color-pal">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop