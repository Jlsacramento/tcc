import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MesasAtendimentoComponent } from './mesas-atendimento.component';

describe('MesasAtendimentoComponent', () => {
  let component: MesasAtendimentoComponent;
  let fixture: ComponentFixture<MesasAtendimentoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MesasAtendimentoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MesasAtendimentoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
