@extends('layouts.master')
@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10">
            Data Work Trip
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                    <button class="btn btn-primary w-28 mb-2" id="approveSelectBtn">Approve Selected</button>
                    <button class="btn btn-danger w-28 ml-2 mb-2" id="rejectSelectBtn">Rejected Selected</button>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <div class="w-56 relative text-slate-500">
                        <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="searchWorktripHt">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table id="myTable" class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="text-center whitespace-nowrap">
                                <input type="checkbox" class="form-check-input" id="select_all_ids">
                            </th>
                            <th data-priority="1" class="whitespace-nowrap">No</th>
                            <th data-priority="2" class="text-center whitespace-nowrap">Name</th>
                            <th class="text-center whitespace-nowrap">Position</th>
                            <th class="text-center whitespace-nowrap">Jensi Kehadiran</th>
                            <th class="text-center whitespace-nowrap">Status</th>
                            <th class="text-center whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workTripData as $item)
                            <tr class="intro-x h-16">
                                <td class="w-4 text-center">
                                    <input type="checkbox" class="form-check-input checkbox_ids" name="ids" value="{{ $item->worktrip->statusCommit->first()->id }}" data-id="{{ $item->id }}">
                                </td>
                                <td class="w-4 text-center">
                                    {{ $loop->iteration }}.
                                </td>
                                <td class="w-50 text-center capitalize">
                                    {{ $item->user->name }}
                                </td>
                                <td class="w-50 text-center capitalize">
                                    {{ $item->user->employee->division->name }}
                                </td>
                                <td class="w-50 text-center capitalize">
                                    {{ ($item->category === 'work_trip' ? 'Work Trip' : $item->category) }}
                                </td>
                                <td class="w-50 text-center capitalize">
                                    {{ $item->worktrip->statusCommit->first()->status }}
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a data-wkHtid="{{ $item->worktrip->statusCommit->first()->id }}" data-messageWK="{{ $item->user->name }} {{ $item->category }}" class="flex items-center text-success mr-3 approve_wk_Ht"
                                            data-Positionid="" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#modal-apprv-wt-search">
                                            <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Approve
                                        </a>
                                        <a class="flex items-center text-warning delete-button mr-3 show-attendance-modal-search-worktrip"
                                            data-avatar="{{ $item->user->employee->avatar }}"
                                            data-gender="{{ $item->user->employee->gender }}"
                                            data-firstname="{{ $item->user->employee->first_name }}"
                                            data-LastName="{{ $item->user->employee->last_name }}"
                                            data-stafId="{{ $item->user->employee->id_number }}"
                                            data-Category="{{ ($item->category === 'work_trip' ? 'Work Trip' : $item->category) }}"
                                            data-Position="{{ $item->user->employee->position->name }}"
                                            data-file="{{ $item->worktrip->file }}"
                                            href="javascript:;" data-tw-toggle="modal" data-tw-target="#show-modal-approve-worktrip">
                                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Detail
                                        </a>
                                        @can('reject_presence')                                            
                                        <a data-rejectwkHtid="{{ $item->worktrip->statusCommit->first()->id }}" data-rejectmessageWK="{{ $item->user->name }} {{ $item->category }}" class="flex items-center text-danger reject_wk_Ht" data-id=""
                                            data-name="" href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#reject-confirmation-modal">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Reject
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- modal approve --}}
    <div id="modal-apprv-wt-search" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="approve-wk-dataHt" method="POST" action="">
                    @csrf
                    @method('put')
                    <div class="modal-body p-0">
                        <div class="p-5 text-center">
                            <i data-lucide="x-circle" class="w-16 h-16 text-success mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Are you sure?</div>
                            <div class="text-slate-500 mt-2" id="subjuduldelete-confirmation">
                                Are you sure you want to approve this absence request?
                            </div>
                            <input hidden name="message" type="text" id="messageWk-approveHt">
                        </div>
                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                            <button type="submit" class="btn btn-success w-24">Approve</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- moda approve end --}}

    {{-- modal multiple approve --}}
    <div id="modal-apprv-wt-search-multiple" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-success mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Are you sure?</div>
                        <div class="text-slate-500 mt-2" id="subjuduldelete-confirmation">
                            Are you sure you want to approve this absence request?
                        </div>
                        <input hidden name="message" type="text" id="messageWk-approveHt">
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                        <button type="submit" class="btn btn-success w-24" id="approveSelectedAll">Approve</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- moda multiple approve end --}}

    {{-- modal rejected --}}
    <div id="reject-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="reject-wk-dataHt" method="POST" action="">
                    @csrf
                    @method('put')
                    <div class="modal-body p-0">
                        <div class="p-5 text-center">
                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Are you sure?</div>
                            <div class="text-slate-500 mt-2" id="subjuduldelete-confirmation">
                                Are you sure you want to reject this absence request?
                            </div>
                            <input name="description" id="crud-form-2" type="text" class="form-control w-full"
                                placeholder="description" required>
                            <input hidden name="message" type="text" id="messageWk-rejectHt">
                        </div>
                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                            <button type="submit" class="btn btn-danger w-24">Reject</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- moda rejected end --}}

    {{-- modal multiple rejected --}}
    <div id="reject-confirmation-modal-multiple" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Are you sure?</div>
                        <div class="text-slate-500 mt-2" id="subjuduldelete-confirmation">
                            Are you sure you want to reject this absence request?
                        </div>
                        <input name="description" id="rejectDesc" type="text" class="form-control w-full"
                            placeholder="description" required>
                        <input hidden name="message" type="text" id="messageWk-rejectHt">
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                        <button type="submit" class="btn btn-danger w-24" id="rejectSelectedAll">Reject</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- moda multiple rejected end --}}

   {{-- detail modal attendance search work_trip --}}
    <div id="show-modal-approve-worktrip" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-lg mx-auto">Detail Kehadiran</h2>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 mx-auto">
                        <div class="w-24 h-24 image-fit zoom-in">
                            <img id="show-modal-image-work" class="tooltip rounded-full" src="">
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="text-xs">Firstname :</label>
                        <input disabled id="Show-firstname-work" type="text" class="form-control" value="">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="text-xs">Lastname :</label>
                        <input disabled id="Show-LastName-work" type="text" class="form-control" value="">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="text-xs">Staff Id :</label>
                        <input disabled id="Show-StafId-work" type="text" class="form-control" value="">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="text-xs">Position :</label>
                        <input disabled id="Show-Posisi-work" type="text" class="form-control" value="">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="text-xs">Category :</label>
                        <input disabled id="Show-Category-work" type="text" class="form-control capitalize" value="">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <div class="flex items-center p-5 form-control">
                            <div class="file"> <div class="w-6 file__icon file__icon--directory"></div></div>
                            <div class="ml-4">
                                <p id="filename" class="font-medium"></p> 
                                <div id="file-size" class="text-slate-500 text-xs mt-0.5"></div>
                            </div>
                            <div class="dropdown ml-auto">
                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a id="put-href-file" href="" class="dropdown-item "> <i data-lucide="download" class="w-4 h-4 mr-2"></i> Download </a>
                                        </li>
                                     </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- detail modal attendance search work_trip end--}}

    <script type="text/javascript">
            // detail work trip modal
            $(document).on("click", ".show-attendance-modal-search-worktrip", function () {
                var ShowGender = $(this).attr('data-gender');
                var showAvatar = $(this).attr('data-avatar');
                var ShowFirstname = $(this).attr('data-firstname');
                var ShowLastName = $(this).attr('data-LastName');
                var ShowStafId = $(this).attr('data-stafId');
                var ShowPosisi = $(this).attr('data-Position');
                var ShowCategory = $(this).attr('data-Category');

                var fileUrl = $(this).attr('data-file');
                var fileName = fileUrl.split('/').pop();
            
                if (fileUrl) {
                    var fileInput = '{{ asset('storage/') }}/' + fileUrl + ''
                    console.log(fileInput);

                    $("#put-href-file").attr('href', fileInput);
                    $("#filename").text(fileName);

                    jQuery(document).ready(function($) {
                    $.ajax({
                        type: "HEAD",
                        url: fileInput,
                        success: function (message, text, jqXhr) {
                            var fileSize = jqXhr.getResponseHeader('Content-Length');
                            var fileSizeKB = (fileSize / 1024).toFixed(2) + ' KB';
                            $("#file-size").text(fileSizeKB);
                        },
                    });
                })
                }

                var imgSrc;
                if(showAvatar){
                    imgSrc = '{{ asset('storage/') }}/' + showAvatar;
                }else if(ShowGender == 'male'){
                    imgSrc = '{{ asset('images/default-boy.jpg') }}';
                }else if(ShowGender == 'female'){
                    imgSrc = '{{ asset('images/default-women.jpg') }}';
                };


                $("#show-modal-image-work").attr('src', imgSrc);
                $("#Show-firstname-work").attr('value', ShowFirstname);
                $("#Show-LastName-work").attr('value', ShowLastName);
                $("#Show-StafId-work").attr('value', ShowStafId);
                $("#Show-Posisi-work").attr('value', ShowPosisi);
                $("#Show-Category-work").attr('value', ShowCategory);
            });

            // approve
            $(document).on("click", ".approve_wk_Ht", function() {
                var ApproveWkModalid = $(this).attr('data-wkHtid');
                var ApproveWkModalMessage = $(this).attr('data-messageWK');

                var formAction;
                formAction = '{{ route('approveht.approvedWorkTripHt', ':id') }}'.replace(':id', ApproveWkModalid);

                $("#approve-wk-dataHt").attr('action', formAction);
                $("#messageWk-approveHt").attr('value', ApproveWkModalMessage);


            });

            // reject
            $(document).on("click", ".reject_wk_Ht", function() {
            var rejectWkModalid = $(this).attr('data-rejectwkHtid');
            var rejectWkModalMessage = $(this).attr('data-rejectmessageWK');

            var formAction;
            formAction = '{{ route('approveht.rejectWorokTripHt', ':id') }}'.replace(':id', rejectWkModalid);

            $("#reject-wk-dataHt").attr('action', formAction);
            $("#messageWk-rejectHt").attr('value', rejectWkModalMessage);


        });

        // checkbox select
        jQuery(document).ready(function($) {
            $("#select_all_ids").click(function(){
                
                // Iterate through all pages
                $('table#myTable').DataTable(). $('.checkbox_ids').attr ('checked',$(this).prop('checked'))
            });
        })

        // check approve checkbox
        jQuery(document).ready(function ($) {
            $("#approveSelectBtn").click(function () {
                var checkedIds = [];

                // Iterate through all pages
                $('table#myTable').DataTable().$('input:checkbox[name=ids]:checked').each(function () {
                    checkedIds.push($(this).data('id'));
                });

                if ($('table#myTable').dataTable().$('input:checkbox[name=ids]:checked').length === 0) {
                    toastr.info('Please select at least one item by checking the checkbox');
                    toastr.options = {
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: 3000
                    };
                } else {
                    console.log('Checked Checkbox for approve : ', checkedIds);

                    $('#approveSelectBtn').attr('data-tw-toggle', 'modal');
                    $('#approveSelectBtn').attr('data-tw-target', '#modal-apprv-wt-search-multiple');
                }
            });
        });

        // check reject checkbox
        jQuery(document).ready(function ($) {
            $("#rejectSelectBtn").click(function () {
                var checkedIds = [];

                // Iterate through all pages
                $('table#myTable').DataTable().$('input:checkbox[name=ids]:checked').each(function () {
                    checkedIds.push($(this).data('id'));
                });

                if ($('table#myTable').dataTable().$('input:checkbox[name=ids]:checked').length === 0) {
                    toastr.info('Please select at least one item by checking the checkbox');
                    toastr.options = {
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: 3000
                    };
                } else {
                    console.log('Checked Checkbox for reject : ', checkedIds);


                    $('#rejectSelectBtn').attr('data-tw-toggle', 'modal');
                    $('#rejectSelectBtn').attr('data-tw-target', '#reject-confirmation-modal-multiple');
                }
            });
        });

        // apprpve multiple
        jQuery(document).ready(function ($) {
            $("#approveSelectedAll").click(function (e) {
                e.preventDefault();
                var all_ids = [];

                // Iterate through all pages
                $('table#myTable').DataTable().$('input:checkbox[name=ids]:checked').each(function () {
                    all_ids.push($(this).val());
                });

                try {
                    $.ajax({
                        url: "{{ route('approvehtMultiple.approvedWorkTripHt') }}",
                        type: "PUT",
                        async: true,
                        data: {
                            ids: all_ids,
                            _token: '{{ csrf_token() }}'
                        },
                        complete: function () {
                        location.reload();
                        console.log('Ajax request complete');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error", error);
                        }
                    });
                } catch (error) {
                    console.error("Error:", error);
                }
            });
        });

        // reject multiple
        jQuery(document).ready(function($) {
            $("#rejectSelectedAll").click(function(e){
                e.preventDefault();
                var all_ids = [];
                var desc = $('input:text[id=rejectDesc]').val();

                if (!desc.trim()) {
                    toastr.info('Please provide a description.');
                    toastr.options = {
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: 3000
                    };
                    return;
                }

                // Iterate through all pages
                $('table#myTable').DataTable().$('input:checkbox[name=ids]:checked').each(function () {
                    all_ids.push($(this).val());
                });

                $.ajax({
                    url: "{{ route('approvehtMultiple.rejectWorokTripHt') }}",
                    type:"PUT",
                    async: true,
                    data:{
                        ids:all_ids,
                        description:desc,
                        _token: '{{ csrf_token() }}'
                    },
                    complete: function () {
                        location.reload();
                        console.log('Ajax request complete');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error", error);
                    }
                });
            })
         });

        // data table
        jQuery(document).ready(function($) {
            var dataTable = new DataTable('#myTable', {
                buttons: ['showSelected'],
                dom: 'rtip',
                select: true, 
                pageLength: 5,
                border: false,
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: [[1, 'asc']]
            });

            $('#searchWorktripHt').on('keyup', function() {
                dataTable.search($(this).val()).draw();
            });
        });
    </script>
@endsection