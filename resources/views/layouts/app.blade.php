<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img') }}/{{ config('app.favicon', 'Laravel') }}">
    <link rel="icon" type="image/png" href="{{ asset('img') }}/{{ config('app.favicon', 'Laravel') }}">
    <link rel="apple-touch-icon" href="{{ asset('img') }}/{{ config('app.favicon', 'Laravel') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel='stylesheet' href='https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

<script>

function dselectUpdate(button, classElement, classToggler) {
  const value = button.dataset.dselectValue
  const target = button.closest(`.${classElement}`).previousElementSibling
  const toggler = target.nextElementSibling.getElementsByClassName(classToggler)[0]
  const input = target.nextElementSibling.querySelector('input')
  if (target.multiple) {
    Array.from(target.options).filter(option => option.value === value)[0].selected = true
  } else {
    target.value = value
  }
  if (target.multiple) {
    toggler.click()
  }
  target.dispatchEvent(new Event('change'))
  toggler.focus()
  if (input) {
    input.value = ''
  }
}
function dselectRemoveTag(button, classElement, classToggler) {
  const value = button.parentNode.dataset.dselectValue
  const target = button.closest(`.${classElement}`).previousElementSibling
  const toggler = target.nextElementSibling.getElementsByClassName(classToggler)[0]
  const input = target.nextElementSibling.querySelector('input')
  Array.from(target.options).filter(option => option.value === value)[0].selected = false
  target.dispatchEvent(new Event('change'))
  toggler.click()
  if (input) {
    input.value = ''
  }
}
function dselectSearch(event, input, classElement, classToggler, creatable) {
  const filterValue = input.value.toLowerCase().trim()
  const itemsContainer = input.nextElementSibling
  const headers = itemsContainer.querySelectorAll('.dropdown-header')
  const items = itemsContainer.querySelectorAll('.dropdown-item')
  const noResults = itemsContainer.nextElementSibling

  headers.forEach(i => i.classList.add('d-none'))

  for (const item of items) {
    const filterText = item.textContent

    if (filterText.toLowerCase().indexOf(filterValue) > -1) {
      item.classList.remove('d-none')
      let header = item
      while(header = header.previousElementSibling) {
        if (header.classList.contains('dropdown-header')) {
          header.classList.remove('d-none')
          break
        }
      }
    } else {
      item.classList.add('d-none')
    }
  }
  const found = Array.from(items).filter(i => !i.classList.contains('d-none') && !i.hasAttribute('hidden'))
  if (found.length < 1) {
    noResults.classList.remove('d-none')
    itemsContainer.classList.add('d-none')
    if (creatable) {
      noResults.innerHTML = `Press Enter to add "<strong>${input.value}</strong>"`
      if (event.key === 'Enter') {
        const target = input.closest(`.${classElement}`).previousElementSibling
        const toggler = target.nextElementSibling.getElementsByClassName(classToggler)[0]
        target.insertAdjacentHTML('afterbegin', `<option value="${input.value}" selected>${input.value}</option>`)
        target.dispatchEvent(new Event('change'))
        input.value = ''
        input.dispatchEvent(new Event('keyup'))
        toggler.click()
        toggler.focus()
      }
    }
  } else {
    noResults.classList.add('d-none')
    itemsContainer.classList.remove('d-none')
  }
}
function dselectClear(button, classElement) {
  const target = button.closest(`.${classElement}`).previousElementSibling
  Array.from(target.options).forEach(option => option.selected = false)
  target.dispatchEvent(new Event('change'))
}
function dselect(el, option = {}) {
  el.style.display = 'none'
  const classElement = 'dselect-wrapper'
  const classNoResults = 'dselect-no-results'
  const classTag = 'dselect-tag'
  const classTagRemove = 'dselect-tag-remove'
  const classPlaceholder = 'dselect-placeholder'
  const classClearBtn = 'dselect-clear'
  const classTogglerClearable = 'dselect-clearable'
  const defaultSearch = false
  const defaultCreatable = false
  const defaultClearable = false
  const defaultMaxHeight = '360px'
  const defaultSize = ''
  const search = attrBool('search') || option.search || defaultSearch
  const creatable = attrBool('creatable') || option.creatable || defaultCreatable
  const clearable = attrBool('clearable') || option.clearable || defaultClearable
  const maxHeight = el.dataset.dselectMaxHeight || option.maxHeight || defaultMaxHeight
  let size = el.dataset.dselectSize || option.size || defaultSize
  size = size !== '' ? ` form-select-${size}` : ''
  const classToggler = `form-select${size}`

  const searchInput = search ? `<input onkeydown="return event.key !== 'Enter'" onkeyup="dselectSearch(event, this, '${classElement}', '${classToggler}', ${creatable})" type="text" class="form-control" placeholder="Search" autofocus>` : ''

  function attrBool(attr) {
    const attribute = `data-dselect-${attr}`
    if (!el.hasAttribute(attribute)) return null

    const value = el.getAttribute(attribute)
    return value.toLowerCase() === 'true'
  }

  function removePrev() {
    if (el.nextElementSibling && el.nextElementSibling.classList && el.nextElementSibling.classList.contains(classElement)) {
      el.nextElementSibling.remove()
    }
  }

  function isPlaceholder(option) {
    return option.getAttribute('value') === ''
  }

  function selectedTag(options, multiple) {
    if (multiple) {
      const selectedOptions = Array.from(options).filter(option => option.selected && !isPlaceholder(option))
      const placeholderOption = Array.from(options).filter(option => isPlaceholder(option))
      let tag = []
      if (selectedOptions.length === 0) {
        const text = placeholderOption.length ? placeholderOption[0].textContent : '&nbsp;'
        tag.push(`<span class="${classPlaceholder}">${text}</span>`)
      } else {
        for (const option of selectedOptions) {
          tag.push(`
            <div class="${classTag}" data-dselect-value="${option.value}">
              ${option.text}
              <svg onclick="dselectRemoveTag(this, '${classElement}', '${classToggler}')" class="${classTagRemove}" width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
            </div>
          `)
        }
      }
      return tag.join('')
    } else {
      const selectedOption = options[options.selectedIndex]
      return isPlaceholder(selectedOption)
      ? `<span class="${classPlaceholder}">${selectedOption.innerHTML}</span>`
      : selectedOption.innerHTML
    }
  }

  function selectedText(options) {
    const selectedOption = options[options.selectedIndex]
    return isPlaceholder(selectedOption) ? '' : selectedOption.textContent
  }

  function itemTags(options) {
    let items = []
    for (const option of options) {
      if (option.tagName === 'OPTGROUP') {
        items.push(`<h6 class="dropdown-header">${option.getAttribute('label')}</h6>`)
      } else {
        const hidden = isPlaceholder(option) ? ' hidden' : ''
        const active = option.selected ? ' active' : ''
        const disabled = el.multiple && option.selected ? ' disabled' : ''
        const value = option.value
        const text = option.textContent
        items.push(`<button${hidden} class="dropdown-item${active}" data-dselect-value="${value}" type="button" onclick="dselectUpdate(this, '${classElement}', '${classToggler}')"${disabled}>${text}</button>`)
      }
    }
    items = items.join('')
    return items
  }

  function createDom() {
    const autoclose = el.multiple ? ' data-bs-auto-close="outside"' : ''
    const additionalClass = Array.from(el.classList).filter(className => {
      return className !== 'form-select'
      && className !== 'form-select-sm'
      && className !== 'form-select-lg'
    }).join(' ')
    const clearBtn = clearable && !el.multiple ? `
    <button type="button" class="btn ${classClearBtn}" title="Clear selection" onclick="dselectClear(this, '${classElement}')">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" fill="none">
        <path d="M13 1L0.999999 13" stroke-width="2" stroke="currentColor"></path>
        <path d="M1 1L13 13" stroke-width="2" stroke="currentColor"></path>
      </svg>
    </button>
    ` : ''
    const template = `
    <div class="dropdown ${classElement} ${additionalClass}">
      <button class="${classToggler} ${!el.multiple && clearable ? classTogglerClearable : ''}" data-dselect-text="${!el.multiple && selectedText(el.options)}" type="button" data-bs-toggle="dropdown" aria-expanded="false"${autoclose}>
        ${selectedTag(el.options, el.multiple)}
      </button>
      <div class="dropdown-menu">
        <div class="d-flex flex-column">
          ${searchInput}
          <div class="dselect-items" style="max-height:${maxHeight};overflow:auto">
            ${itemTags(el.querySelectorAll('*'))}
          </div>
          <div class="${classNoResults} d-none">No results found</div>
        </div>
      </div>
      ${clearBtn}
    </div>
    `
    removePrev()
    el.insertAdjacentHTML('afterend', template) // insert template after element
  }
  createDom()

  function updateDom() {
    const dropdown = el.nextElementSibling
    const toggler = dropdown.getElementsByClassName(classToggler)[0]
    const dSelectItems = dropdown.getElementsByClassName('dselect-items')[0]
    toggler.innerHTML = selectedTag(el.options, el.multiple)
    dSelectItems.innerHTML = itemTags(el.querySelectorAll('*'))
    if (!el.multiple) {
      toggler.dataset.dselectText = selectedText(el.options)
    }
  }

  el.addEventListener('change', updateDom)
}
</script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm position-sticky" style="top:0;">
            <div class="container">
                <!--<a class="navbar-brand" href="{{ url('/') }}">
                MVMS{{ config('app.name', 'Laravel') }}
                </a>-->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto lg">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                        @else
                            @if(session()->has('is_switched') && session('is_switched') == true)
                              <a class="nav-link sa-switcher" href="{{ url()->current() }}?switch_back">
                                <i class="fa fa-arrow-left"></i> Switch Back as SA
                              </a>
                            @endif
                            @if(Auth::user()->role == 1)
                              <a class="nav-link user-logs" href="{{ route('user-logs') }}">
                                <i class="fa fa-eye"></i> User Logs
                              </a>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @section('sidebar')
            @show
            @yield('content')
        </main>
    </div>

<script>
$(document).ready(function() {
    $('table').DataTable({ paging: false, ordering: false });  
} );
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $('.delete-item').click(function(){
    Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to proceed?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
          
          var action = $(this).data('action');
          var type = $(this).data('type');
          window.location.href = '{{ url('/delete') }}/' + action;

        }
    });
  });
  
  $('.restore-item').click(function(){
    Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to proceed?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, restore it!'
    }).then((result) => {
        if (result.isConfirmed) {
          
          var action = $(this).data('action');
          var type = $(this).data('type');
          window.location.href = '{{ url('/restore') }}/' + action;

        }
    });
  });
  
  $('.unassign').click(function(){
    Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to proceed?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, unassign!'
    }).then((result) => {
        if (result.isConfirmed) {
          
          var action = $(this).data('action');
          var type = $(this).data('type');
          window.location.href = '{{ url('/') }}/' + action;

        }
    });
  });
  
  $('.detach').click(function(){
    Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to proceed?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, detach!'
    }).then((result) => {
        if (result.isConfirmed) {
          
          var action = $(this).data('action');
          var type = $(this).data('type');
          window.location.href = '{{ url('/detach') }}/' + action;

        }
    });
  });
</script>

<script src='https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js'></script>
<script src='https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js'></script>
<script src='https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js'></script>

</body>
</html>