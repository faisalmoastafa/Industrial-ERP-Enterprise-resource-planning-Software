<script src="{{ asset('vendor/filepond/filepond-plugin-image-preview.js') }}"></script>
<script src="{{ asset('vendor/filepond/filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ asset('vendor/filepond/filepond-plugin-file-validate-type.js') }}"></script>
<script src="{{ asset('vendor/filepond/filepond.js') }}"></script>

<script>
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType
    );
    const fileElement = document.querySelector('input[id="image"]');
    const pond = FilePond.create(fileElement, {
        acceptedFileTypes: ['image/png', 'image/jpg', 'image/jpeg'],
    });
    FilePond.setOptions({
        server: {
            process: "{{ route('filepond.upload') }}",
            revert: "{{ route('filepond.delete') }}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        }
    });
</script>
