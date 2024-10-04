            <style>
                .doc-link {
                    font-size: 13px;
                    font-weight: 600;
                }

                .info .accordion-button {
                    padding: 0;
                    font-weight: 700;
                    font-size: 16px;
                    background: none !important;
                    border: none !important;
                }

                .info .accordion-button::after {
                    display: none !important;
                }

                .info.accordion {
                    --bs-accordion-btn-focus-box-shadow: none !important;
                    --bs-accordion-border-width: 0 !important;
                }

                .info .accordion-body {
                    border: 1px solid #80808052;
                    margin-top: 10px;
                    border-radius: 10px;
                }
            </style>
            <div class="row mb-4 justify-content-center">
                <div class="col-md-4">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Store Name:</dt>
                        <dd class="col-sm-7">{{ $store->name }}</dd>

                        <dt class="col-sm-4">Address:</dt>
                        <dd class="col-sm-7">{{ $store->address }}</dd>

                        <dt class="col-sm-4">Phone:</dt>
                        <dd class="col-sm-7">{{ $store->phone }}</dd>

                        <dt class="col-sm-4">Fax:</dt>
                        <dd class="col-sm-7">{{ $store->fax }}</dd>

                        <dt class="col-sm-4">Size:</dt>
                        <dd class="col-sm-7">{{ $store->size }}</dd>
                        
                        @if($store->llc)
                        <dt class="col-sm-4">Company Entity:</dt>
                        <dd class="col-sm-7">{{ $store->llc }}</dd>
                        @endif

                        @if($store->ein)
                        <dt class="col-sm-4">EIN:</dt>
                        <dd class="col-sm-7">{{ $store->ein }}</dd>
                        @endif

                        @if($store->blue_print_path)
                        <dt class="col-sm-4">Blue Print:</dt>
                        <dd class="col-sm-7"><a class="doc-link" href="{{ url('/store-docs') }}/{{ $store->blue_print_path }}" target="_blank" download>DOWNLOAD</a></dd>
                        @endif

                        @if($store->doc_1_path)
                        <dt class="col-sm-4">Doc 1:</dt>
                        <dd class="col-sm-7"><a class="doc-link" href="{{ url('/store-docs') }}/{{ $store->doc_1_path }}" target="_blank" download>DOWNLOAD</a></dd>
                        @endif

                        @if($store->doc_2_path)
                        <dt class="col-sm-4">Doc 2:</dt>
                        <dd class="col-sm-7"><a class="doc-link" href="{{ url('/store-docs') }}/{{ $store->doc_2_path }}" target="_blank" download>DOWNLOAD</a></dd>
                        @endif
                        
                        @if($store->expenses && Auth::user()->role < 3)
                        <div class="info accordion" id="expensesAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="expenseHeadingOne">
                                <button class=" accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#expenseCollapseOne" aria-expanded="false" aria-controls="expenseCollapseOne">
                                    Expenses
                                    <i class="fas fa-chevron-down float-end" style="margin-left: 60px;"></i>
                                </button>
                                </h2>
                                <div id="expenseCollapseOne" class="accordion-collapse collapse" aria-labelledby="expenseHeadingOne" data-bs-parent="#expensesAccordion">
                                <div class="accordion-body">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Cost Name</th>
                                                <th>Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(json_decode($store->expenses, true) as $expense)
                                            <tr>
                                                <td>{{ $expense['cost_name'] }}</td>
                                                <td><span>$</span>{{ $expense['cost'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </dl>
                </div>
                <div class="col-md-7">
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-4 d-flex align-self-center justify-content-end text-center">
                            <div class="count-box p-3 d-flex align-items-center justify-content-between" style="border-bottom: 4px solid #ffc863;">
                                <div class="text-start">
                                    <div class="count-box-inner h3 mb-0" id="employees-count">0</div>
                                    <div class="count-box-label h6">Employees</div>
                                </div>
                                <i class="fa fa-users info-icon" style="background: #ffc863; font-size: 18px; padding: 11px 9px;"></i>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-self-center justify-content-center text-center">
                            <div class="count-box p-3 d-flex align-items-center justify-content-between" style="border-bottom: 4px solid #7bbe67;">
                                <div class="text-start">
                                    <div class="count-box-inner h3 mb-0" id="licenses-count">0</div>
                                    <div class="count-box-label h6">Licenses</div>
                                </div>
                                <i class="fa fa-certificate info-icon" style="background: #7bbe67;"></i>
                            </div>
                        </div>                        
                        <div class="col-md-4 d-flex align-self-center justify-content-start text-center">
                            <div class="count-box p-3 d-flex align-items-center justify-content-between" style="border-bottom: 4px solid #c06ecc;">
                                <div class="text-start">
                                    <div class="count-box-inner h3 mb-0" id="equipments-count">0</div>
                                    <div class="count-box-label h6">Equipments</div>
                                </div>
                                <i class="fa fa-tools info-icon" style="background: #c06ecc;"></i>
                            </div>
                        </div>                        
                    </div>
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-4 d-flex align-self-center justify-content-end text-center">
                            <div class="count-box p-3 d-flex align-items-center justify-content-between" style="border-bottom: 4px solid #79d6d4;">
                                <div class="text-start">
                                    <div class="count-box-inner h3 mb-0" id="fuel-tanks-count">0</div>
                                    <div class="count-box-label h6">Fuel Tanks</div>
                                </div>
                                <i class="fa-solid fa-water info-icon" style="background: #79d6d4; font-size: 18px; padding: 11px 10px;"></i>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-self-center justify-content-center text-center">
                            <div class="count-box p-3 d-flex align-items-center justify-content-between" style="border-bottom: 4px solid #d16161;">
                                <div class="text-start">
                                    <div class="count-box-inner h3 mb-0" id="pumps-count">0</div>
                                    <div class="count-box-label h6">Pumps</div>
                                </div>
                                <i class="fas fa-gas-pump info-icon" style="background: #d16161;"></i>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-self-center justify-content-start text-center">
                            <div class="count-box p-3 d-flex align-items-center justify-content-between" style="border-bottom: 4px solid #6c6c6c;">
                                <div class="text-start">
                                    <div class="count-box-inner h3 mb-0" id="wo-count">0</div>
                                    <div class="count-box-label h6">Work Orders</div>
                                </div>
                                <i class="fa fa-calendar-days info-icon" style="background: #6c6c6c; font-size: 18px; padding: 11px 12px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<style>
  main {
    background: #f8f8f8;
  }

  .tab-content {
    background: white;
    border-radius: 0 10px 10px 10px;
  }
  
  .count-box {
    position: relative;
    width: 100%;
    height: 90px;
    border-radius: 5px;
    margin-bottom: 1rem;
    overflow: hidden;
    box-shadow: 0 0 10px #00000026;
  }

  .count-box-label {
    font-size: 0.8rem;
  }

  .info-icon {
    font-size: 20px;
    padding: 10px;
    border-radius: 50px;
    color: white;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const equipmentsCount = document.getElementById('equipments-count');
    const employeesCount = document.getElementById('employees-count');
    const licensesCount = document.getElementById('licenses-count');
    const fuel_tanksCount = document.getElementById('fuel-tanks-count');
    const pumpsCount = document.getElementById('pumps-count');
    const wo_Count = document.getElementById('wo-count');
    

    function animateCountCircle(countElement, count) {
        let currentCount = 0;
        const increment = count / 100;
        const animationInterval = setInterval(function() {
            if (currentCount >= count) {
                clearInterval(animationInterval);
            } else {
                currentCount += increment;
                countElement.textContent = Math.round(currentCount);
            }
        }, 10);
    }

    @if($store->equipments)
        animateCountCircle(equipmentsCount, {{ $store->equipments->count() }});
    @endif

    @if($store->users)
        animateCountCircle(employeesCount, {{ $store->users->whereNotIn('role', [1, 2])->count() }});
    @endif
    
    @if($store->store_licenses)
        animateCountCircle(licensesCount, {{ $store->store_licenses->count() }});
    @endif

    @if($store->fuel_tanks)
        animateCountCircle(fuel_tanksCount, {{ $store->fuel_tanks->count() }});
    @endif

    @if($store->pumps)
        animateCountCircle(pumpsCount, {{ $store->pumps->count() }});
    @endif

    @if($store->work_orders)
        animateCountCircle(wo_Count, {{ $store->work_orders->count() }});
    @endif

});
</script>

<script>
  $(document).ready(function(){
    $('#expensesAccordion .accordion-button').click(function(){
      $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    });
  });
</script>