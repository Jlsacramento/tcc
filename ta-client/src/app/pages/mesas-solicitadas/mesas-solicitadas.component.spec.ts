import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MesasSolicitadasComponent } from './mesas-solicitadas.component';

describe('MesasSolicitadasComponent', () => {
  let component: MesasSolicitadasComponent;
  let fixture: ComponentFixture<MesasSolicitadasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MesasSolicitadasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MesasSolicitadasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
