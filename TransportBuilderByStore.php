
@package magento/framework

Index: Mail/Template/TransportBuilderByStore.php
IDEA additional info:
Subsystem: com.intellij.openapi.diff.impl.patch.CharsetEP
<+>UTF-8
===================================================================
--- Mail/Template/TransportBuilderByStore.php	(revision fb55bb2774fd6e2e4d4ea892372121836d7d319a)
+++ Mail/Template/TransportBuilderByStore.php	(date 1544600040000)
@@ -8,6 +8,13 @@
 
 use Magento\Framework\Mail\MessageInterface;
 
+/**
+ * Class TransportBuilderByStore
+ *
+ * @deprecated The ability to set From address based on store is now available
+ * in the \Magento\Framework\Mail\Template\TransportBuilder class
+ * @see \Magento\Framework\Mail\Template\TransportBuilder::setFromByStore
+ */
 class TransportBuilderByStore
 {
     /**
Index: Mail/Test/Unit/Template/TransportBuilderTest.php
IDEA additional info:
Subsystem: com.intellij.openapi.diff.impl.patch.CharsetEP
<+>UTF-8
===================================================================
--- Mail/Test/Unit/Template/TransportBuilderTest.php	(revision fb55bb2774fd6e2e4d4ea892372121836d7d319a)
+++ Mail/Test/Unit/Template/TransportBuilderTest.php	(date 1544600040000)
@@ -164,19 +164,20 @@
     /**
      * @return void
      */
-    public function testSetFrom()
+    public function testSetFromByStore()
     {
         $sender = ['email' => 'from@example.com', 'name' => 'name'];
+        $store = 1;
         $this->senderResolverMock->expects($this->once())
             ->method('resolve')
-            ->with($sender)
+            ->with($sender, $store)
             ->willReturn($sender);
         $this->messageMock->expects($this->once())
             ->method('setFrom')
             ->with('from@example.com', 'name')
             ->willReturnSelf();
 
-        $this->builder->setFrom($sender);
+        $this->builder->setFromByStore($sender, $store);
     }
 
     /**
Index: Mail/Template/TransportBuilder.php
IDEA additional info:
Subsystem: com.intellij.openapi.diff.impl.patch.CharsetEP
<+>UTF-8
===================================================================
--- Mail/Template/TransportBuilder.php	(revision fb55bb2774fd6e2e4d4ea892372121836d7d319a)
+++ Mail/Template/TransportBuilder.php	(date 1544600040000)
@@ -173,12 +173,29 @@
     /**
      * Set mail from address
      *
+     * @deprecated This function sets the from address for the first store only.
+     * new function setFromByStore introduced to allow setting of from address
+     * based on store.
+     * @see setFromByStore()
+     *
      * @param string|array $from
      * @return $this
      */
     public function setFrom($from)
     {
-        $result = $this->_senderResolver->resolve($from);
+        return $this->setFromByStore($from, null);
+    }
+
+    /**
+     * Set mail from address by store
+     *
+     * @param string|array $from
+     * @param string|int $store
+     * @return $this
+     */
+    public function setFromByStore($from, $store = null)
+    {
+        $result = $this->_senderResolver->resolve($from, $store);
         $this->message->setFrom($result['email'], $result['name']);
         return $this;
     }