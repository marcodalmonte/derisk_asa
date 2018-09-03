@extends('layouts.blank')

@section('main-content')
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#inspectionTable-{{ $floor }}').DataTable({
                'responsive':   true,
                'lengthChange': false,
                'iDisplayLength': 20,
                'ordering':     false,
                scrollY:        false,
                scrollX:        true,
                scrollCollapse: true,
                paging:         false,
                fixedColumns:   {
                    leftColumns: 1
                }
            });
    
            jQuery('#add-inspection-{{ $floor }}').bootstrapValidator({
                excluded: [':disabled'],
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: { }
            });
        });
    </script>

    <form id="add-inspection-{{ $floor }}" 
        class="add-inspection"
        method="post" 
        data-bv-message="This value is not valid"
        data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
        data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
        data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

        {{ csrf_field() }}

        <input type="hidden" id="job_number_{{ $floor }}" name="job_number" value="{{ $job_number }}" />
        <input type="hidden" id="ukas_number_{{ $floor }}" name="ukas_number" value="{{ $ukasnumber }}" />
        <input type="hidden" id="floor_{{ $floor }}" name="floor" value="{{ $floor }}" />

        <div class="clearfix"></div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-light">
                <div class="panel-heading">
                    <button type="button" id="update-all-{{ $floor }}" name="update-all" onclick="updateInspections('{{ $floor }}')" class="btn btn-info btn-lg">Save all</button>
                    <button type="button" id="export-all-{{ $floor }}" name="export-all" onclick="exportInspections('{{ $floor }}')" class="btn btn-info btn-lg">Export all</button>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="inspectionTable-{{ $floor }}" class="inspectionTable table table-striped table-bordered no-margin responsiveTable">
                            <thead>
                                <tr>
                                    <th>N&#176;</th>
                                    <th>Referral<br/>Presumed</th>
                                    <th>Picture</th>
                                    <th>Building<br/>Room<br/>Room Name</th>
                                    <th>Product<br/>Surface Treatment<br/>Quantity</th>
                                    <th>Access<br/>Accessibility</th>
                                    <th>Results</th>
                                    <th>Comments</th>
                                    <th>Material / Location</th>
                                    <th>Recommendations<br/>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="insp_new-{{ $floor }}" class="insp insp-floor-{{ $floor }}">
                                    <td>
                                        New
                                    </td>
                                    <td>
                                        <input type="text" id="inspectionNumber_new_{{ $floor }}" name="inspectionNumber_new_{{ $floor }}" class="form-control thin" value="" title="Inspection Number" />
                                        <select id="referenced_new_{{ $floor }}" name="referenced_new_{{ $floor }}" class="form-control">
                                            <option value="">Ref</option>
                                    @foreach ($refinspections as $reference)
                                            <option value="{{ $reference->id }}">{{ $reference->inspection_number }}</option>
                                    @endforeach
                                        </select>
                                        <select id="presumed_new_{{ $floor }}" name="presumed_new_{{ $floor }}" class="form-control">
                                            <option value="1">Yes</option>
                                            <option value="0" selected>No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div id="picture_container_new_{{ $floor }}" class="picture_container">
                                    
                                        </div>
                                        <input type="file" id="upload_new_{{ $floor }}" name="upload_new_{{ $floor }}" class="hide" value="" accept="image/*" onchange="loadFile(event,'new_{{ $floor }}','new')" /> 
                                        <label id="label_picture_new_{{ $floor }}" for="upload_new_{{ $floor }}" class="btn btn-info">Select file</label>

                                        <input type="hidden" id="picture_new_{{ $floor }}" name="picture_new_{{ $floor }}" value="" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control large" id="building_new_{{ $floor }}" name="building_new_{{ $floor }}" value="" />
                                        
                                        <select id="room_new_{{ $floor }}" name="room_new_{{ $floor }}" class="form-control thin">
                                            <option value="">--</option>
                                    @foreach ($rooms as $curroom)
                                            <option value="{{ $curroom->id }}">{{ $curroom->name }}</option>
                                    @endforeach
                                        </select>
                                        <input type="text" class="form-control large" id="room_name_new_{{ $floor }}" name="room_name_new_{{ $floor }}" value="" title="Room Name" />
                                        <select id="extent_new_{{ $floor }}" name="extent_new_{{ $floor }}" class="form-control large" title="Extent of Damage">
                                            <option value="">--</option>
                                    @foreach ($extents as $curextent)
                                            <option value="{{ $curextent->code }}">{{ $curextent->name }}</option>
                                    @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select id="product_new_{{ $floor }}" name="product_new_{{ $floor }}" class="form-control">
                                            <option value="">--</option>
                                    @foreach ($products as $curproduct)
                                            <option value="{{ $curproduct->id }}">{{ $curproduct->name }}</option>
                                    @endforeach
                                        </select>
                                        <select id="treatment_new_{{ $floor }}" name="treatment_new_{{ $floor }}" class="form-control treatments">
                                            <option value="">--</option>
                                    @foreach ($treatments as $curtreatment)
                                            <option value="{{ $curtreatment->id }}">{{ $curtreatment->description }}</option>
                                    @endforeach
                                        </select>
                                        <input type="text" class="form-control" id="quantity_new_{{ $floor }}" name="quantity_new_{{ $floor }}" value="" />
                                    </td>
                                    <td>
                                        <select id="accessible_new_{{ $floor }}" name="accessible_new_{{ $floor }}" class="form-control accesses">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <select id="accessibility_new_{{ $floor }}" name="accessibility_new_{{ $floor }}" class="form-control accesses">
                                            <option value=""></option>
                                            <option title="Difficult" value="Difficult">D</option>
                                            <option title="Readily" value="Readily">R</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea id="results_new_{{ $floor }}" name="results_new_{{ $floor }}" class="form-control"></textarea>
                                    </td>
                                    <td>
                                        <textarea id="comments_new_{{ $floor }}" name="comments_new_{{ $floor }}" class="form-control"></textarea>
                                    </td>
                                    <td>
                                        <textarea id="material_location_new_{{ $floor }}" name="material_location_new_{{ $floor }}" class="form-control"></textarea>
                                    </td>
                                    <td>
                                        <textarea id="recommendations_new_{{ $floor }}" name="recommendations_new_{{ $floor }}" class="form-control"></textarea>
                                        <textarea id="recommendationsNotes_new_{{ $floor }}" name="recommendationsNotes_new_{{ $floor }}" class="form-control"></textarea>
                                    </td>
                                    <td>
                                        <a href="javascript:;" title="Save inspection information" onclick="saveNewInspection('{{ $floor }}')" class="actions new_entity save">
                                            <img src="/img/update.png" class="img_actions" alt="Save inspection information" title="Save inspection information" />
                                        </a>
                                    </td>
                                </tr>
                                @foreach ($inspections as $curinspection)
                                <tr id="insp_{{ $curinspection->id }}" class="insp-floor-{{ $floor }}">
                                    <td>
                                        {{ $curinspection->inspection_number }}
                                        <input type="hidden" id="inspectionNumber_{{ $curinspection->id }}" name="inspectionNumber_{{ $curinspection->id }}" value="{{ $curinspection->inspection_number }}" />
                                    </td>
                                    <td>
                                        <select id="referenced_{{ $curinspection->id }}" name="referenced_{{ $curinspection->id }}" class="form-control">
                                            <option value="">Ref</option>
                                    @php
                                        foreach ($refinspections as $reference) {
                                            $selected = '';

                                            if ($reference->id == $curinspection->id) {
                                                continue;
                                            }

                                            if ($curinspection->referral == $reference->id) {
                                                $selected = ' selected';
                                            }
                                    @endphp
                                            <option value="{{ $reference->id }}"{{ $selected }}>{{ $reference->inspection_number }}</option>
                                    @php
                                        }
                                    @endphp
                                        </select>
                                        <select id="presumed_{{ $curinspection->id }}" name="presumed_{{ $curinspection->id }}" class="form-control">
                                            <option value="1"{{ $curinspection->presumed ? ' selected' : ''}}>Yes</option>
                                            <option value="0"{{ $curinspection->presumed ? '' : ' selected'}}>No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div id="picture_container_{{ $curinspection->id }}" class="picture_container">
                                    @php
                                        if (!empty($curinspection->photo)) {
                                    @endphp
                                            <img src="<?php echo '/tablet' . $curinspection->photo ?>" alt="{{ $curinspection->inspection_number }}" title="{{ $curinspection->inspection_number }}" id="photo_{{ $curinspection->id }}" class="inspections_picture" />
                                    @php
                                        }        
                                    @endphp
                                        </div>
                                        <input type="file" id="upload_{{ $curinspection->id }}" name="upload_{{ $curinspection->id }}" class="hide" value="" accept="image/*" onchange="loadFile(event,{{ $curinspection->id }},'{{ $curinspection->inspection_number }}')" /> 
                                        <label id="label_picture_{{ $curinspection->id }}" for="upload_{{ $curinspection->id }}" class="btn btn-info">Select file</label>

                                        <input type="hidden" id="picture_{{ $curinspection->id }}" name="picture_{{ $curinspection->id }}" value="<?php echo (!empty($curinspection->photo) ? ('/tablet' . $curinspection->photo) : '') ?>" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control large" id="building_{{ $curinspection->id }}" name="building_{{ $curinspection->id }}" value="{{ $curinspection->building }}" />
                                        
                                        <select id="room_{{ $curinspection->id }}" name="room_{{ $curinspection->id }}" class="form-control thin">
                                            <option value="">--</option>
                                    @php
                                        foreach ($rooms as $curroom) {
                                            $selected = '';

                                            if ($curinspection->room_id == $curroom->id) {
                                                $selected = ' selected';
                                            }
                                    @endphp
                                            <option value="{{ $curroom->id }}"{{ $selected }}>{{ $curroom->name }}</option>
                                    @php
                                        }
                                    @endphp
                                        </select>
                                        <input type="text" class="form-control large" id="room_name_{{ $curinspection->id }}" name="room_name_{{ $curinspection->id }}" value="{{ $curinspection->room_name }}" />
                                    </td>
                                    <input type="hidden" id="extent_{{ $curinspection->id }}" name="extent_{{ $curinspection->id }}" value="{{ $curinspection->extent_of_damage }}" />
                                    <td>
                                        <select id="product_{{ $curinspection->id }}" name="product_{{ $curinspection->id }}" class="form-control">
                                            <option value="">--</option>
                                    @php
                                        foreach ($products as $curproduct) {
                                            $selected = '';

                                            if ($curinspection->product_id == $curproduct->id) {
                                                $selected = ' selected';
                                            }
                                    @endphp
                                            <option value="{{ $curproduct->id }}"{{ $selected }}>{{ $curproduct->name }}</option>
                                    @php
                                        }
                                    @endphp
                                        </select>
                                        <select id="treatment_{{ $curinspection->id }}" name="treatment_{{ $curinspection->id }}" class="form-control treatments">
                                            <option value="">--</option>
                                    @php
                                        foreach ($treatments as $curtreatment) {
                                            $selected = '';

                                            if ($curinspection->surface_treatment == $curtreatment->id) {
                                                $selected = ' selected';
                                            }
                                    @endphp
                                            <option value="{{ $curtreatment->id }}"{{ $selected }}>{{ $curtreatment->description }}</option>
                                    @php
                                        }
                                    @endphp
                                        </select>
                                        <input type="text" class="form-control" id="quantity_{{ $curinspection->id }}" name="quantity_{{ $curinspection->id }}" value="{{ $curinspection->quantity }}" />
                                    </td>
                                    <td>
                                        <select id="accessible_{{ $curinspection->id }}" name="accessible_{{ $curinspection->id }}" class="form-control accesses">
                                            <option value="1"{{ $curinspection->accessible ? ' selected' : ''}}>Yes</option>
                                            <option value="0"{{ $curinspection->accessible ? '' : ' selected'}}>No</option>
                                        </select>
                                        <select id="accessibility_{{ $curinspection->id }}" name="accessibility_{{ $curinspection->id }}" class="form-control accesses">
                                            <option value=""></option>
                                            <option title="Difficult" value="Difficult"<?php if ('Difficult' == trim($curinspection->accessibility)) { echo ' selected'; } ?>>D</option>
                                            <option title="Readily" value="Readily"<?php if ('Readily' == trim($curinspection->accessibility)) { echo ' selected'; } ?>>R</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea id="results_{{ $curinspection->id }}" name="results_{{ $curinspection->id }}" class="form-control">{{ $curinspection->results }}</textarea>
                                    </td>
                                    <td>
                                        <textarea id="comments_{{ $curinspection->id }}" name="comments_{{ $curinspection->id }}" class="form-control">{{ $curinspection->comments }}</textarea>
                                    </td>
                                    <td>
                                        <textarea id="material_location_{{ $curinspection->id }}" name="material_location_{{ $curinspection->id }}" class="form-control">{{ $curinspection->material_location }}</textarea>
                                    </td>
                                    <td>
                                        <textarea id="recommendations_{{ $curinspection->id }}" name="recommendations_{{ $curinspection->id }}" class="form-control">{{ $curinspection->recommendations }}</textarea>
                                        <textarea id="recommendationsNotes_{{ $curinspection->id }}" name="recommendationsNotes_{{ $curinspection->id }}" class="form-control">{{ $curinspection->recommendationsNotes }}</textarea>
                                    </td>
                                    <td>
                                        <a href="javascript:;" title="Update inspection information" onclick="updateInspection('{{ $curinspection->id }}','{{ $floor }}','1')" class="actions new_entity update">
                                            <img src="/img/update.png" class="img_actions" alt="Update inspection information" title="Update inspection information" />
                                        </a>
                                        <a href="javascript:;" title="Delete Inspection" onclick="deleteInspection({{ $curinspection->id }},'{{ $floor }}')" class="actions delete-inspection">
                                            <img src="/img/delete.png" class="img_actions" alt="Delete Inspection" title="Delete Inspection" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="dialog-confirm-{{ $floor }}" class="dialog-confirm" title="Are you sure?">
        <p><span class="ui-icon ui-icon-alert"></span>Are you sure to delete this inspection?</p>
    </div>
@endsection