#ifndef FINDDIALOG_H
#define FINDDIALOG_H

#include <QDialog>

// forward declaration
class QCheckBox;
class QLabel;
class QLineEdit;
class QPushButton;

class FindDialog : public QDialog
{
	// necessary macro to enable signal and slot
	Q_OBJECT;

public:
	FindDialog(QWidget* parent = 0);
	
	~FindDialog(); 
	
signals: // signal section	
	void findNext(const QString&, Qt::CaseSensitivity); // Qt::CaseSensitivity is enum
	void findPrevious(const QString&, Qt::CaseSensitivity);
	
private slots:
	void findClicked();
	void enableFindButton(const QString&);
	
private:
	QLabel *label;
	QLineEdit *lineEdit;
	QCheckBox *caseCheckBox;
	QCheckBox *backwardCheckBox;
	QPushButton *findButton;
	QPushButton *closeButton;
};

#endif
