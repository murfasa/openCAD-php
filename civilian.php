<?php

/**
Open source CAD system for RolePlaying Communities.
Copyright (C) 2017 Shane Gill

This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

This program comes with ABSOLUTELY NO WARRANTY; Use at your own risk.
**/

    require('./oc-config.php');
    require("./oc-functions.php");
    include("./actions/civActions.php");
    session_start();

    // TODO: Verify user has permission to be on this page

    if (empty($_SESSION['logged_in']))
    {
        header('Location: ./index.php');
        die("Not logged in");
    }
    else
    {
      $name = $_SESSION['name'];
    }

    $errors = array();
    // define variables and set to empty values
    $civNameReq = "";

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    $civName = $civDob = $civAddr = "";

    $good911 = "";
    if(isset($_SESSION['good911']))
    {
        $good911 = $_SESSION['good911'];
        unset($_SESSION['good911']);
    }

    $identityMessage = "";
    if(isset($_SESSION['identityMessage']))
    {
        $identityMessage = $_SESSION['identityMessage'];
        unset($_SESSION['identityMessage']);
    }


?>

<!DOCTYPE html>
<html lang="en">
  <?php include "./oc-includes/header.inc.php"; ?>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="javascript:void(0)" class="site_title"><i class="fa fa-tachometer"></i> <span><?php echo COMMUNITY_NAME;?> Civilian</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="<?php echo get_avatar() ?>" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $name;?></h2>
              </div>
              <div class="clearfix"></div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li class="active"><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display: block;">
                      <li class="current-page"><a href="javascript:void(0)">Civilian Dashboard</a></li>-
                    </ul>
                  </li>
                </ul>
              </div>
              <!-- ./ menu_section -->
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings" href="./profile.php">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Go to Dashboard" href="dashboard.php">
                <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="./actions/logout.php?responder=<?php echo $_SESSION['identifier'];?>">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo get_avatar() ?>" alt=""><?php echo $_SESSION['name']; ?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                      <li><a href="./profile.php">My Profile</a></li>
                      <li><a href="./actions/logout.php?responder=<?php echo $_SESSION['identifier'];?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                      <span class="glyphicon glyphicon-log">
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>CAD Civilian</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>My Identities</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <!-- ./ x_title -->
                  <div class="x_content">
                    <?php getIdentities();?>
                  </div>
                  <!-- ./ x_content -->
                </div>
                <!-- ./ x_panel -->
              </div>
              <!-- ./ col-md-12 col-sm-12 col-xs-12 -->
            </div>
            <!-- ./ row -->

            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Request an Identity</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <!-- ./ x_title -->
                  <form name="civRequestForm" method="post" action="./actions/civActions.php">
                  <div class="x_content">
                  <?php echo $identityMessage;?>
                    <div class="form-group row">
                      <label class="col-md-2 control-label">Name</label>
                      <div class="col-md-10">
                        <input name="civNameReq" class="form-control" id="civNameReq" value="<?php echo $civName;?>" required/>
                        <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->

                    <div class="form-group row">
                      <label class="col-md-2 control-label">DOB</label>
                      <div class="col-md-10">
                        <input type="text" name="civDobReq" class="form-control" id="civDobReq" placeholder="YYYY-MM-DD" maxlength="10" value="<?php echo $civDob;?>" required/>
                        <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->

                    <div class="form-group row">
                      <label class="col-md-2 control-label">Address</label>
                      <div class="col-md-10">
                        <input type="text" name="civAddressReq" class="form-control" id="civAddressReq" value="<?php echo $civAddr;?>" required/>
                        <span class="fa fa-location-arrow form-control-feedback right" aria-hidden="true"></span>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->

                    <div class="form-group row">
                      <label class="col-md-2 control-label">Sex</label>
                      <div class="col-md-10">
                        <select name="civSexReq" class="form-control selectpicker" id="civSexReq" title="Select a sex" required>
                          <option val="male">Male</option>
                          <option val="female">Female</option>
                        </select>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->

                    <div class="form-group row">
                      <label class="col-md-2 control-label">Race</label>
                      <div class="col-md-10">
                        <select name="civRaceReq" class="form-control selectpicker" id="civRaceReq" title="Select a race or ethnicity" required>
                          <option val="indian">American Indian or Alaskan Native</option>
                          <option val="asian">Asian</option>
                          <option val="black">Black or African American</option>
                          <option val="hispanic">Hispanic</option>
                          <option val="hawaiian">Native Hawaiian or Other Pacific Islander</option>
                          <option val="white">White</option>
                        </select>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->

                    <div class="form-group row">
                      <label class="col-md-2 control-label">Hair Color</label>
                      <div class="col-md-10">
                        <select name="civHairReq" class="form-control selectpicker" id="civHairReq" title="Select a hair color" required>
                          <option val="bld">Bald</option>
                          <option val="blk">Black</option>
                          <option val="bln">Blond or Strawberry</option>
                          <option val="blu">Blue</option>
                          <option val="bro">Brown</option>
                          <option val="gry">Gray or Partially Gray</option>
                          <option val="grn">Green</option>
                          <option val="ong">Orange</option>
                          <option val="pnk">Pink</option>
                          <option val="ple">Purple</option>
                          <option val="red">Red or Auburn</option>
                          <option val="sdy">Sandy</option>
                          <option val="whi">White</option>
                        </select>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->

                    <div class="form-group row">
                      <label class="col-md-2 control-label">Build</label>
                      <div class="col-md-10">
                        <select name="civBuildReq" class="form-control selectpicker" id="civBuildReq" title="Select a build" required>
                          <option val="Average">Average</option>
                          <option val="Fit">Fit</option>
                          <option val="Muscular">Muscular</option>
                          <option val="Overweight">Overweight</option>
                          <option val="Skinny">Skinny</option>
                          <option val="Thin">Thin</option>
                        </select>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->

                    <div class="form-group row">
                      <label class="col-md-2 control-label">Biography</label>
                      <div class="col-md-10">
                        <textarea name="civBioReq" class="form-control" id="civBioReq" rows='5' style="resize:none;" placeholder="Describe the character's biography"></textarea>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->

                    <h4>Vehicle Details</h4>
                    <div class="form-group row">
                      <label class="col-md-2 control-label">License Plate</label>
                      <div class="col-md-10">
                        <input type="text" name="civPlateReq" class="form-control" id="civPlateReq" style="text-transform:uppercase" maxlength="7" required/>
                        <span class="fa fa-car form-control-feedback right" aria-hidden="true"></span>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->
                    <div class="form-group row">
                      <label class="col-md-2 control-label">Vehicle Make</label>
                      <div class="col-md-10">
                        <input type="text" name="civMakeReq" class="form-control" id="civMakeReq" style="text-transform:uppercase" required/>
                        <span class="fa fa-car form-control-feedback right" aria-hidden="true"></span>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->
                    <div class="form-group row">
                      <label class="col-md-2 control-label">Vehicle Model</label>
                      <div class="col-md-10">
                        <input type="text" name="civModelReq" class="form-control" id="civModelReq" style="text-transform:uppercase" required/>
                        <span class="fa fa-car form-control-feedback right" aria-hidden="true"></span>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->
                    <div class="form-group row">
                    <!-- color codes from: http://publicsafety.ohio.gov/links/bmv5607.pdf -->
                      <label class="col-md-2 control-label">Vehicle Color</label>
                      <div class="col-md-10">
                        <select name="civVehColReq" class="form-control selectpicker" data-live-search="true" data-size="8" id="civVehColReq" title="Select a vehicle color" required>
                          <option val="lbl">Light Blue</option>
                          <option val="trq">Turqoise</option>
                          <option val="dbl">Dark Blue</option>
                          <option val="blu">Blue</option>
                          <option val="ame">Amethyst</option>
                          <option val="ple">Purple</option>
                          <option val="lav">Lavender</option>
                          <option val="mve">Mauve</option>
                          <option val="pnk">Pink</option>
                          <option val="red">Red</option>
                          <option val="mar">Maroon</option>
                          <option val="ong">Orange</option>
                          <option val="cpr">Copper</option>
                          <option val="brz">Bronze</option>
                          <option val="tan">Tan</option>
                          <option val="gld">Gold</option>
                          <option val="yel">Yellow</option>
                          <option val="lgr">Light Green</option>
                          <option val="grn">Green</option>
                          <option val="dgr">Dark Green</option>
                          <option val="tea">Teal</option>
                          <option val="bro">Brown</option>
                          <option val="crm">Cream</option>
                          <option val="bge">Beige</option>
                          <option val="tpe">Taupe</option>
                          <option val="sil">Silver</option>
                          <option val="com">Chrome</option>
                          <option val="gry">Gray</option>
                          <option val="blk">Black</option>
                          <option val="whi">White</option>
                          <option val="cam">Camouflage</option>
                          <option val="mul">Multi-Colored</option>
                        </select>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group -->
                  </div>
                  <!-- ./ x_content -->
                  <div class="x_footer">
                    <input type="submit" class="btn btn-primary" name="requestIdentity" value="Submit Identity Request"/>
                  </div>
                  </form>
                </div>
                <!-- ./ x_panel -->
              </div>
              <!-- ./ col-md-6 col-sm-6 col-xs-6 -->

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>New 911 Call</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <!-- ./ x_title -->
                  <div class="x_content">
                    <?php echo $good911;?>
                    <form id="new_911" method="post" action="./actions/api.php">
                    <div class="form-group row">
                      <label class="col-md-2 control-label">Caller Name</label>
                      <div class="col-md-10">
                        <input type="text" name="911_caller" class="form-control" id="911_caller" required/>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group row -->
                    <div class="form-group row">
                      <label class="col-md-2 control-label">Location</label>
                      <div class="col-md-10">
                        <input type="text" name="911_location" class="form-control" id="911_location" required/>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group row -->
                    <div class="form-group row">
                      <label class="col-md-2 control-label"><span>Description <a data-toggle="modal" href="#911CallHelpModal"><i class="fa fa-question-circle"></i></a></span></label>
                      <div class="col-md-10">
                        <textarea id="911_description" name="911_description" class="form-control" style="resize:none;" rows="4"></textarea>
                      </div>
                      <!-- ./ col-sm-9 -->
                    </div>
                    <!-- ./ form-group row -->
                  </div>
                  <!-- ./ x_content -->
                  <div class="x_footer">
                    <button type="reset" class="btn btn-default" value="Reset">Reset</button>
                    <input type="submit" class="btn btn-primary" name="new_911" value="Submit 911 Call"/>
                  </div>
                  <!-- ./ x_footer -->
                  </form>
                </div>
                <!-- ./ x_panel -->
              </div>
              <!-- ./ col-md-6 col-sm-6 col-xs-6 -->
            </div>
            <!-- ./ row -->

          </div>
          <!-- "" -->
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            <?php echo COMMUNITY_NAME;?> CAD System
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- modals -->
    <!-- Civilian Details Modal -->
    <div class="modal fade" id="civilianDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Civilian Details</h4>
          </div>
          <!-- ./ modal-header -->
          <div class="modal-body">
            <h4>Character Details</h4>
              <div class="form-group row">
                <label class="col-md-3 control-label">Name</label>
                <div class="col-md-9">
                  <input name="civName" class="form-control" id="civName" disabled/>
                  <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">DOB</label>
                <div class="col-md-9">
                  <input type="text" name="civDob" class="form-control" id="civDob" disabled/>
                  <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Address</label>
                <div class="col-md-9">
                  <input type="text" name="civAddress" class="form-control" id="civAddress" disabled/>
                  <span class="fa fa-location-arrow form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Sex</label>
                <div class="col-md-9">
                  <input type="text" name="civSex" class="form-control" id="civSex" disabled/>
                  <span class="fa fa-transgender form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Race</label>
                <div class="col-md-9">
                  <input type="text" name="civRace" class="form-control" id="civRace" disabled/>
                  <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Hair Color</label>
                <div class="col-md-9">
                  <input type="text" name="civHair" class="form-control" id="civHair" disabled/>
                  <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Build</label>
                <div class="col-md-9">
                  <input type="text" name="civBuild" class="form-control" id="civBuild" disabled/>
                  <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Biography</label>
                <div class="col-md-9">
                  <textarea name="civBio" class="form-control" id="civBio" rows='4' disabled>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In libero odio, commodo volutpat tellus elementum, feugiat iaculis nisl. Vivamus convallis augue nec semper suscipit. Donec eu odio interdum erat iaculis venenatis. Curabitur velit tortor, imperdiet ac est ac, tincidunt mattis elit. In hac habitasse platea dictumst. Aliquam tincidunt odio quis convallis ullamcorper. </textarea>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <h4>Vehicle Details</h4>
              <div class="form-group row">
                <label class="col-md-3 control-label">License Plate</label>
                <div class="col-md-9">
                  <input type="text" name="civPlate" class="form-control" id="civPlate" disabled/>
                  <span class="fa fa-car form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Vehicle Make</label>
                <div class="col-md-9">
                  <input type="text" name="civMake" class="form-control" id="civMake" disabled/>
                  <span class="fa fa-car form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Vehicle Model</label>
                <div class="col-md-9">
                  <input type="text" name="civModel" class="form-control" id="civModel" disabled/>
                  <span class="fa fa-car form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
              <div class="form-group row">
                <label class="col-md-3 control-label">Vehicle Color</label>
                <div class="col-md-9">
                  <input type="text" name="civColor" class="form-control" id="civColor" disabled/>
                  <span class="fa fa-car form-control-feedback right" aria-hidden="true"></span>
                </div>
                <!-- ./ col-sm-9 -->
              </div>
              <!-- ./ form-group -->
          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>
    <!-- ./ modal fade bs-example-modal-lg -->

    <!-- modals -->
    <!-- 911 Call Help Modal -->
    <div class="modal fade" id="911CallHelpModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">How to Submit a 911 Call</h4>
          </div>
          <!-- ./ modal-header -->
          <div class="modal-body">
            <span>
              <p><b>Where, What, Who, When, How, Why if available are the primary things to provide to a 911 dispatcher.</b></p>
              <p>Some things to consider reporting:</p>
              <p>
                <ul><li>Your name</li>
                <li>Address responders need to go to</li>
                <li>Any weapons?</li>
                <li>Age of suspect(s) or victim(s)</li>
                <li>Height and Weight of suspect(s)</li>
                <li>Clothing description of suspect(s)</li>
                <li>Drug use (current or past, includes perscription medications) of any victim(s)</li>
                <li>Any prior violent behavior</li>
                <li>Any prior information about psychosis, delusions, hallucinations or other mental health considerations</li>
                </ul>
              </p>
            </span>
          </div>
          <!-- ./ modal-body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <!-- ./ modal-footer -->
        </div>
        <!-- ./ modal-content -->
      </div>
      <!-- ./ modal-dialog modal-lg -->
    </div>
    <!-- ./ modal fade bs-example-modal-lg -->

    <?php include "./oc-includes/jquery-colsolidated.inc.php"; ?>

    <script>
    $('#civilianDetailsModal').on('show.bs.modal', function(e) {
    var $modal = $(this), civId = e.relatedTarget.id;


    $.ajax({
        cache: false,
        type: 'GET',
        url: './actions/civActions.php',
        data: {'getCivilianDetails': 'yes',
                'name_id' : civId},
        success: function(result)
        {
            console.log(result);
            data = JSON.parse(result);

            $('input[name="civName"]').val(data['name']);
            $('input[name="civDob"]').val(data['dob']);
            $('input[name="civAddress"]').val(data['address']);
            $('input[name="civSex"]').val(data['sex']);
            $('input[name="civRace"]').val(data['race']);
            $('input[name="civHair"]').val(data['hair_color']);
            $('input[name="civBuild"]').val(data['build']);
            $('input[name="civPlate"]').val(data['veh_plate']);
            $('input[name="civMake"]').val(data['veh_make']);
            $('input[name="civModel"]').val(data['veh_model']);
            $('input[name="civColor"]').val(data['veh_color']);


        },

        error:function(exception){alert('Exeption:'+exception);}
        });
    });
    </script>

  </body>
</html>
