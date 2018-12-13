@package magento/module-sales

Index: Model/Order/Email/SenderBuilder.php
IDEA additional info:
Subsystem: com.intellij.openapi.diff.impl.patch.CharsetEP
<+>UTF-8
===================================================================
--- Model/Order/Email/SenderBuilder.php	(revision fb55bb2774fd6e2e4d4ea892372121836d7d319a)
+++ Model/Order/Email/SenderBuilder.php	(date 1544600040000)
@@ -5,7 +5,6 @@
  */
 namespace Magento\Sales\Model\Order\Email;
 
-use Magento\Framework\App\ObjectManager;
 use Magento\Framework\Mail\Template\TransportBuilder;
 use Magento\Framework\Mail\Template\TransportBuilderByStore;
 use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
@@ -29,11 +28,8 @@
     protected $transportBuilder;
 
     /**
-     * @var TransportBuilderByStore
-     */
-    private $transportBuilderByStore;
-
-    /**
+     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
+     *
      * @param Template $templateContainer
      * @param IdentityInterface $identityContainer
      * @param TransportBuilder $transportBuilder
@@ -48,9 +44,6 @@
         $this->templateContainer = $templateContainer;
         $this->identityContainer = $identityContainer;
         $this->transportBuilder = $transportBuilder;
-        $this->transportBuilderByStore = $transportBuilderByStore ?: ObjectManager::getInstance()->get(
-            TransportBuilderByStore::class
-        );
     }
 
     /**
@@ -110,7 +103,7 @@
         $this->transportBuilder->setTemplateIdentifier($this->templateContainer->getTemplateId());
         $this->transportBuilder->setTemplateOptions($this->templateContainer->getTemplateOptions());
         $this->transportBuilder->setTemplateVars($this->templateContainer->getTemplateVars());
-        $this->transportBuilderByStore->setFromByStore(
+        $this->transportBuilder->setFromByStore(
             $this->identityContainer->getEmailIdentity(),
             $this->identityContainer->getStore()->getId()
         );
Index: Test/Unit/Model/Order/Email/SenderBuilderTest.php
IDEA additional info:
Subsystem: com.intellij.openapi.diff.impl.patch.CharsetEP
<+>UTF-8
===================================================================
--- Test/Unit/Model/Order/Email/SenderBuilderTest.php	(revision fb55bb2774fd6e2e4d4ea892372121836d7d319a)
+++ Test/Unit/Model/Order/Email/SenderBuilderTest.php	(date 1544600040000)
@@ -6,7 +6,6 @@
 
 namespace Magento\Sales\Test\Unit\Model\Order\Email;
 
-use Magento\Framework\Mail\Template\TransportBuilderByStore;
 use Magento\Sales\Model\Order\Email\SenderBuilder;
 
 class SenderBuilderTest extends \PHPUnit\Framework\TestCase
@@ -36,11 +35,6 @@
      */
     private $storeMock;
 
-    /**
-     * @var \PHPUnit_Framework_MockObject_MockObject
-     */
-    private $transportBuilderByStore;
-
     protected function setUp()
     {
         $templateId = 'test_template_id';
@@ -82,10 +76,9 @@
                 'setTemplateIdentifier',
                 'setTemplateOptions',
                 'setTemplateVars',
+                'setFromByStore',
             ]
         );
-
-        $this->transportBuilderByStore = $this->createMock(TransportBuilderByStore::class);
 
         $this->templateContainerMock->expects($this->once())
             ->method('getTemplateId')
@@ -109,9 +102,9 @@
         $this->identityContainerMock->expects($this->once())
             ->method('getEmailIdentity')
             ->will($this->returnValue($emailIdentity));
-        $this->transportBuilderByStore->expects($this->once())
+        $this->transportBuilder->expects($this->once())
             ->method('setFromByStore')
-            ->with($this->equalTo($emailIdentity));
+            ->with($this->equalTo($emailIdentity), 1);
 
         $this->identityContainerMock->expects($this->once())
             ->method('getEmailCopyTo')
@@ -120,8 +113,7 @@
         $this->senderBuilder = new SenderBuilder(
             $this->templateContainerMock,
             $this->identityContainerMock,
-            $this->transportBuilder,
-            $this->transportBuilderByStore
+            $this->transportBuilder
         );
     }
 
@@ -129,6 +121,8 @@
     {
         $customerName = 'test_name';
         $customerEmail = 'test_email';
+        $identity = 'email_identity_test';
+
         $transportMock = $this->createMock(
             \Magento\Sales\Test\Unit\Model\Order\Email\Stub\TransportInterfaceMock::class
         );
@@ -151,6 +145,9 @@
         $this->storeMock->expects($this->once())
             ->method('getId')
             ->willReturn(1);
+        $this->transportBuilder->expects($this->once())
+            ->method('setFromByStore')
+            ->with($identity, 1);
         $this->transportBuilder->expects($this->once())
             ->method('addTo')
             ->with($this->equalTo($customerEmail), $this->equalTo($customerName));
@@ -164,6 +161,7 @@
 
     public function testSendCopyTo()
     {
+        $identity = 'email_identity_test';
         $transportMock = $this->createMock(
             \Magento\Sales\Test\Unit\Model\Order\Email\Stub\TransportInterfaceMock::class
         );
@@ -177,6 +175,9 @@
         $this->transportBuilder->expects($this->once())
             ->method('addTo')
             ->with($this->equalTo('example@mail.com'));
+        $this->transportBuilder->expects($this->once())
+            ->method('setFromByStore')
+            ->with($identity, 1);
         $this->identityContainerMock->expects($this->once())
             ->method('getStore')
             ->willReturn($this->storeMock);