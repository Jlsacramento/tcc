import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MesasLivresComponent } from './mesas-livres.component';

describe('MesasLivresComponent', () => {
  let component: MesasLivresComponent;
  let fixture: ComponentFixture<MesasLivresComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MesasLivresComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MesasLivresComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
