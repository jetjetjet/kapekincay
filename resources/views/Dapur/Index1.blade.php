@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Meja</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Meja</a></li>
  </ol>
@endsection

@section('content-table')
<style>
  .cards tbody tr {
    float: left;
    width: 19rem;
    margin: 0.5rem;
    border: 0.0625rem solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
    box-shadow: 0.25rem 0.25rem 0.5rem rgba(0, 0, 0, 0.25);
  }

  .cards tbody td {
    display: block;
  }

  .cards thead {
    display: none;
  }
  .cards td:before {
    content: attr(data-label);
    position: relative;
    float: left;
    color: #808080;
    min-width: 4rem;
    margin-left: 0;
    margin-right: 1rem;
    text-align: left;   
  }

  tr.selected td:before {
    color: #CCC;
  }

  .table .avatar {
    width: 50px;
  }

  .cards .avatar {
    width: 150px;
    margin: 15px;
  }
</style>

  <div class="widget-content widget-content-area br-6">
    <div class="table-responsive mb-4 mt-4">
      <table id="example" class="table table-sm table-hover cards" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Position</th>
            <th>Salary</th>
            <th>Start</th>
            <th>Office</th>
            <th>Office</th>
            <th>Office</th>
            <th>Extn</th>
          </tr>
        </thead>
    </div>
  </div>
@endsection

@section('js-table')
  <script>
  const data = [
        {
            "id": "1",
            "name": "Tiger Nixon",
            "position": "System Architect",
            "salary": "$320,800",
            "start_date": "2011\/04\/25",
            "office": "Edinburgh",
            "extn": "5421"
        },
        {
            "id": "2",
            "name": "Garrett Winters",
            "position": "Accountant",
            "salary": "$170,750",
            "start_date": "2011\/07\/25",
            "office": "Tokyo",
            "extn": "8422"
        },
        {
            "id": "3",
            "name": "Ashton Cox",
            "position": "Junior Technical Author",
            "salary": "$86,000",
            "start_date": "2009\/01\/12",
            "office": "San Francisco",
            "extn": "1562"
        },
        {
            "id": "4",
            "name": "Cedric Kelly",
            "position": "Senior Javascript Developer",
            "salary": "$433,060",
            "start_date": "2012\/03\/29",
            "office": "Edinburgh",
            "extn": "6224"
        },
        {
            "id": "5",
            "name": "Airi Satou",
            "position": "Accountant",
            "salary": "$162,700",
            "start_date": "2008\/11\/28",
            "office": "Tokyo",
            "extn": "5407"
        },
        {
            "id": "6",
            "name": "Brielle Williamson",
            "position": "Integration Specialist",
            "salary": "$372,000",
            "start_date": "2012\/12\/02",
            "office": "New York",
            "extn": "4804"
        },
        {
            "id": "7",
            "name": "Herrod Chandler",
            "position": "Sales Assistant",
            "salary": "$137,500",
            "start_date": "2012\/08\/06",
            "office": "San Francisco",
            "extn": "9608"
        },
        {
            "id": "8",
            "name": "Rhona Davidson",
            "position": "Integration Specialist",
            "salary": "$327,900",
            "start_date": "2010\/10\/14",
            "office": "Tokyo",
            "extn": "6200"
        },
        {
            "id": "9",
            "name": "Colleen Hurst",
            "position": "Javascript Developer",
            "salary": "$205,500",
            "start_date": "2009\/09\/15",
            "office": "San Francisco",
            "extn": "2360"
        },
        {
            "id": "10",
            "name": "Sonya Frost",
            "position": "Software Engineer",
            "salary": "$103,600",
            "start_date": "2008\/12\/13",
            "office": "Edinburgh",
            "extn": "1667"
        },
        {
            "id": "11",
            "name": "Jena Gaines",
            "position": "Office Manager",
            "salary": "$90,560",
            "start_date": "2008\/12\/19",
            "office": "London",
            "extn": "3814"
        },
        {
            "id": "12",
            "name": "Quinn Flynn",
            "position": "Support Lead",
            "salary": "$342,000",
            "start_date": "2013\/03\/03",
            "office": "Edinburgh",
            "extn": "9497"
        },
        {
            "id": "13",
            "name": "Charde Marshall",
            "position": "Regional Director",
            "salary": "$470,600",
            "start_date": "2008\/10\/16",
            "office": "San Francisco",
            "extn": "6741"
        },
        {
            "id": "14",
            "name": "Haley Kennedy",
            "position": "Senior Marketing Designer",
            "salary": "$313,500",
            "start_date": "2012\/12\/18",
            "office": "London",
            "extn": "3597"
        }
    ]

    $(document).ready(function (){
      var table = $('#example').DataTable({
        'paging':   false,
        'ordering': false,
        'data': data,
        'dom':
            // "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'<'float-md-right ml-2'B>f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
         'buttons': [, {
            'text': '<i class="fa fa-id-badge fa-fw" aria-hidden="true"></i>',
            'action': function (e, dt, node) {

               $(dt.table().node()).toggleClass('cards');
              //  $('.fa', node).toggleClass(['fa-table', 'fa-id-badge']);

               dt.draw('page');
            },
            'className': 'btn-sm',
            'attr': {
               'title': 'Change views',
            }
         }],
         'select': 'single',
         'columns': [
            {
               'orderable': false,
               'data': null,
               'className': 'text-center',
               'render': function(data, type, full, meta){
                  data = "Meja No.";
                  
                  return data;
               }
            },
            {
               'data': 'name'
            },
            {
               'data': 'position'
            },
            {
               'data': 'salary',
               'class': 'text-right'
            },
            {
               'data': 'start_date',
               'class': 'text-right'
            },
            {
               'data': 'office'
            },
            {
               'data': 'office'
            },
            {
               'data': 'office'
            },
            {
               'data': 'extn'
            }
         ],
         'drawCallback': function (settings) {
            var api = this.api();
            var $table = $(api.table().node());
            
            if ($table.hasClass('cards')) {

               // Create an array of labels containing all table headers
               var labels = [];
               $('thead th', $table).each(function () {
                  labels.push($(this).text());
               });

               // Add data-label attribute to each cell
               $('tbody tr', $table).each(function () {
                  $(this).find('td').each(function (column) {
                     $(this).attr('data-label', labels[column]);
                  });
               });

               var max = 0;
               $('tbody tr', $table).each(function () {
                  max = Math.max($(this).height(), max);
               }).height(max);

            } else {
               // Remove data-label attribute from each cell
               $('tbody td', $table).each(function () {
                  $(this).removeAttr('data-label');
               });

               $('tbody tr', $table).each(function () {
                  $(this).height('auto');
               });
            }
         }
      })
    });

    
  </script>
@endsection
