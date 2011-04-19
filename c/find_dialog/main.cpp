#include <QApplication>
//#include <QHBoxLayout>
//#include <QSlider>
//#include <QSpinBox>
#include "find_dialog.h"

/*
int main_old(int argc, char** argv)
{
	QApplication app(argc, argv);
	
	QWidget *window = new QWidget;
	window->setWindowTitle("A qt window");
	
	QSpinBox *spinbox = new QSpinBox;
	QSlider *slider = new QSlider;
	spinbox->setRange(1, 130);
	slider->setRange(1, 130);
	
	QObject::connect(slider, SIGNAL(valueChanged(int)), spinbox, SLOT(setValue(int)) );
	QObject::connect(spinbox, SIGNAL(valueChanged(int)), slider, SLOT(setValue(int)) );
	
	QHBoxLayout *layout = new QHBoxLayout;
	layout->addWidget(slider);
	layout->addWidget(spinbox);
	
	window->setLayout(layout);
	window->show();
	return app.exec();
}
*/

int main(int argc, char** argv)
{
	QApplication app(argc, argv);
	
	FindDialog *dialog = new FindDialog;	
	dialog->show();
	
	//delete dialog;
	
	return app.exec();
}
