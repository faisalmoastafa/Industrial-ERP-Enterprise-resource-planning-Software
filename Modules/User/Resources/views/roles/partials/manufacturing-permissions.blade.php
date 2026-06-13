<div class="tab-pane fade" id="panel-manufacturing" role="tabpanel">
    <div class="permission-section-title">Production Batch Costing</div>
    <div class="row">
        @php
            $manufacturingPermissions = [
                'access_manufacturing' => 'Access Manufacturing',
                'create_production_batches' => 'Create Batch',
                'show_production_batches' => 'View Batch',
                'delete_production_batches' => 'Delete Batch',
            ];
        @endphp

        @foreach($manufacturingPermissions as $permission => $label)
            <div class="col-md-4 mb-3">
                <div class="custom-control custom-switch">
                    <input type="checkbox"
                           class="custom-control-input"
                           id="{{ $permission }}"
                           name="permissions[]"
                           value="{{ $permission }}"
                           @if(isset($role))
                               {{ $role->permissions->contains(fn ($rolePermission) => $rolePermission->name === $permission) ? 'checked' : '' }}
                           @else
                               {{ in_array($permission, old('permissions', []), true) ? 'checked' : '' }}
                           @endif>
                    <label class="custom-control-label" for="{{ $permission }}">{{ $label }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>
