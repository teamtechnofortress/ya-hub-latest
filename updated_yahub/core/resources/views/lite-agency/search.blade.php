@extends('layouts.lite-agency') @section('content') <div class="p-sm-4 p-3 project">
    <h1>Search Projects</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text"
                        class="form-control search-input"
                        placeholder="Search here..."
                        name="search"
                        id="search" />
                </div>
            </div>
        </div>
        <div class="pro-content">
            <div class="pro-row">
                <div class="row pb-3 my-3 search-area">
                    <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                        <div class="project-box border shadow py-2 px-3">
                            <div class="d-flex justify-content-between project-h">
                                <div>
                                    <h2>Type in the search bar.</h2>
                                </div>
                            </div>
                            <div class="budget d-flex justify-content-between align-items-center">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(".search-input").on("keyup", function() {
    var query = $(this).val();
    if (query.length == 0) {
        $(".search-area").html("");
        $('.search-area').append(` <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                    <div class="project-box border shadow py-2 px-3">
                        <div class="d-flex justify-content-between project-h">
                            <div>
                                <h2>Type in the search bar.</h2>
                            </div>
                        </div>
                        <div class="budget d-flex justify-content-between align-items-center">
                        </div>
                    </div>
                </div>`);
        return;
    }
    $.ajax({
        url: "{{route('lite-agency-search-ajax')}}",
        data: {
            query: query
        },
        type: "POST",
        dataType: "JSON",
        success: function(result) {
            $(".search-area").html("");
            if (result.projects.length > 0) {
                var projects = result.projects;
                projects.forEach(function(project, i) {
                    $(".search-area").append(`<div id="project-${project.id}"
                    class="project-container col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                    <div class="project-box border shadow py-2 px-3">
                        <div class="d-flex justify-content-between project-h">
                            <div>
                                <h2>${project.project_title}</h2>
                                <span>${project.client_name}</span>
                            </div>
                            <div>
                                <a href="#" class="btn"><i class="fas fa-ellipsis-v"></i></a>
                            </div>
                        </div>
                        <p>${project.project_description}</p>
                        <div class="budget d-flex justify-content-between align-items-center">
                            <div>
                                <h3>Budget : <span> ${project.project_budget}</span></h3>
                            </div>
                            <div>
                                <a href="{{url('projects/')}}/${project.id}" class="btn">View Project</a>
                            </div>
                        </div>
                    </div>
                </div> `);
                })
            } else {
                $('.search-area').append(` <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                    <div class="project-box border shadow py-2 px-3">
                        <div class="d-flex justify-content-between project-h">
                            <div>
                                <h2>No projects found.</h2>
                            </div>
                        </div>
                        <div class="budget d-flex justify-content-between align-items-center">
                        </div>
                    </div>
                </div>`);
            }
        },
    })
})
</script> @endsection
