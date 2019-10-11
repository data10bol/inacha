<div class="row">
  <div class="col-12">
    <div class="card" style="background-color: #dc3545">
      <div class="card-title align-top text-center">
        <h3 class="card-title">
          <span style="color: white; font-weight: bold">
              {{ $name }}
          </span>
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-header no-border">
                <div>
                  <h3 class="card-title">
                    <strong>
                      <span>Avance anual</span>
                    </strong>
                  </h3>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <div id="charta"
                       style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                  {!! $chartaccuma !!}
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-header no-border">
                <div>
                  <h3 class="card-title">
                    <strong>
                      <strong>
                        <span>Avance de {{ $month }}</span>
                      </strong>
                    </strong>
                  </h3>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <div id="chartm"
                       style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                  {!! $chartaccumm !!}
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-header no-border">
                <div>
                  <h3 class="card-title">
                    <strong>
                      <strong>
                        <span>Avance acumulado a {{ $month }}</span>
                      </strong>
                    </strong>
                  </h3>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <div id="chartam"
                       style="min-width: 450px; height: 350px; margin: 0 auto"></div>
                  {!! $chartaccumma !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
