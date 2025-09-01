
@foreach($lst_project_teams  as $index => $team_info)
    <tr  class="odd gradeX" data-ptm_id="{{ $team_info->ptm_id }}">
        <td><input type="checkbox" name="ck_ptm_{{ $team_info->ptm_id }}" id="CK_PTM_{{ $team_info->ptm_id }}" class="checkboxes" value="{{ $team_info->ptm_id }}" /></td>
        <td>{{ $team_info->ptm_id }}</td>
        <td>{{ $team_info->Team->ut_team }}</td>
        <td>{{ $team_info->ptm_allocation_pct }}</td>
        <td>{{ $team_info->ptm_start_date }}</td>
        <td>{{ $team_info->ptm_end_date }}</td>
        <td><a href="#"  data-ptm_id="{{ $team_info->ptm_id }}" id="UNLINK_TEAM_{{ $team_info->ptm_id  }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
