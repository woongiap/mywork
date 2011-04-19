#include <QtGui>
#include "find_dialog.h"

FindDialog::FindDialog(QWidget* parent)
	:QDialog(parent)
{
	label = new QLabel("find &what:");
	lineEdit = new QLineEdit;
	label->setBuddy(lineEdit);
	
	caseCheckBox = new QCheckBox(tr("match &case"));
	backwardCheckBox = new QCheckBox(tr("find &backward"));
	
	findButton = new QPushButton(tr("&find"));
	findButton->setDefault(true);
	findButton->setEnabled(false);
	
	closeButton = new QPushButton(tr("&close"));
	
	connect(lineEdit, SIGNAL(textChanged(const QString&)),
					findButton, SLOT(enableFindButton(const QString&)));
					
	connect(findButton, SIGNAL(clicked()),
					this, SLOT(findClicked()));
					
	connect(closeButton, SIGNAL(clicked()),
					this, SLOT(close()));
					
	// layout widgets
	QHBoxLayout *topLeftLayout = new QHBoxLayout;
	topLeftLayout->addWidget(label);
	topLeftLayout->addWidget(lineEdit);

	QVBoxLayout *leftLayout = new QVBoxLayout;
	leftLayout->addLayout(topLeftLayout);
	leftLayout->addWidget(caseCheckBox);
	leftLayout->addWidget(backwardCheckBox);

	QVBoxLayout *rightLayout = new QVBoxLayout;
	rightLayout->addWidget(findButton);
	rightLayout->addWidget(closeButton);
	rightLayout->addStretch();

	QHBoxLayout *mainLayout = new QHBoxLayout;
	mainLayout->addLayout(leftLayout);
	mainLayout->addLayout(rightLayout);
	setLayout(mainLayout);
	
	setWindowTitle(tr("find_dialog"));
	setFixedHeight(sizeHint().height());	
}

void FindDialog::findClicked()
{
		QString text = lineEdit->text();
		Qt::CaseSensitivity cs = caseCheckBox->isChecked() ? Qt::CaseSensitive:Qt::CaseInsensitive;
		
		if(backwardCheckBox->isChecked())
		{
			emit findNext(text, cs);
		}
		else
		{
			emit findPrevious(text, cs);
		}
			
}

void FindDialog::enableFindButton(const QString& str) 
{
	findButton->setEnabled(!str.isEmpty());
}

FindDialog::~FindDialog()
{
	delete (label);
	delete lineEdit;
	delete caseCheckBox;
	delete backwardCheckBox;
	delete findButton;
	delete closeButton;
}

